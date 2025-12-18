<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use PDF; // barryvdh/laravel-dompdf
use Illuminate\Support\Facades\Log;
use App\Mail\PurchaseReceiptMail;

class CheckoutController extends Controller
{
    protected $payuUrlSandbox = 'https://test.payu.in/_payment';
    protected $payuUrlProd    = 'https://secure.payu.in/_payment';

    /**
     * Create payment session and redirect to PayU (hosted flow).
     * Expects POST with: name, email, phone, course_slug, currency
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|max:150',
            'phone'    => 'required|string|max:30',
            'course'   => 'required|string',
            'currency' => 'required|in:INR,USD',
        ]);

        // Map course slug -> price (USD or INR)
        $prices = [
            'forex-mastery'       => ['USD' => 499, 'INR' => 20000],
            'price-action'        => ['USD' => 249, 'INR' => 20000],
            'intraday-swing'      => ['USD' => 399, 'INR' => 20000],
            'advanced-psychology' => ['USD' => 449, 'INR' => 25000],
        ];

        $slug = $request->input('course');
        if (! array_key_exists($slug, $prices)) {
            return back()->with('error', 'Invalid course selected.');
        }

        $currency = $request->input('currency', 'USD');
        $amount = $prices[$slug][$currency] ?? $prices[$slug]['USD'];
        if (! $amount) {
            return back()->with('error', 'Unable to determine course price.');
        }

        $key  = env('PAYU_KEY');
        $salt = env('PAYU_SALT');
        $mode = env('PAYU_MODE', 'sandbox');

        $txnid = 'gofx_' . Str::random(12) . time();
        $productinfo = "GOFX - " . $this->titleFromSlug($slug);
        $amountStr   = number_format((float)$amount, 2, '.', '');

        // Build post data
        $data = [
            'key'          => $key,
            'txnid'        => $txnid,
            'amount'       => $amountStr,
            'productinfo'  => $productinfo,
            'firstname'    => $request->input('name'),
            'email'        => $request->input('email'),
            'phone'        => $request->input('phone'),
            'udf1'         => $slug,
            'udf2'         => $currency,
            'udf3'         => '',
            'udf4'         => '',
            'udf5'         => '',
            'surl'         => route('payment.success'),
            'furl'         => route('payment.failure'),
            'service_provider' => 'payu_paisa',
        ];

        // Generate hash — per PayU Hosted checkout docs: sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||salt) :contentReference[oaicite:1]{index=1}
        $hashString = implode('|', [
            $data['key'],
            $data['txnid'],
            $data['amount'],
            $data['productinfo'],
            $data['firstname'],
            $data['email'],
            $data['udf1'],
            $data['udf2'],
            $data['udf3'],
            $data['udf4'],
            $data['udf5'],
            '', '', '', '', '',  // placeholders if more udf/empty fields expected
            $salt,
        ]);
        $data['hash'] = hash('sha512', $hashString);

        // Save minimal session info (guest flow)
        session()->put('payu.checkout.' . $txnid, [
            'txnid'    => $txnid,
            'course'   => $slug,
            'currency' => $currency,
            'amount'   => $amountStr,
            'firstname'=> $data['firstname'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
        ]);

        // Logging
        try {
            if (app()->bound('dblog')) {
                app('dblog')->info('Checkout initiated', ['txnid' => $txnid, 'course' => $slug, 'email' => $data['email']]);
            } else {
                Log::info('Checkout initiated', ['txnid' => $txnid, 'course' => $slug, 'email' => $data['email']]);
            }
        } catch (\Throwable $e) {
            Log::error('DBLog (checkout) failed: '.$e->getMessage());
        }

        $payuUrl = $mode === 'production' ? $this->payuUrlProd : $this->payuUrlSandbox;

        return view('checkout.redirect', [
            'payuUrl'  => $payuUrl,
            'postData' => $data,
        ]);
    }

    /**
     * PayU Success (surl)
     */
    public function success(Request $request)
    {
        $posted = $request->all();
        $txnid  = $posted['txnid'] ?? null;
        $status = $posted['status'] ?? null;

        // Logging callback
        try {
            if (app()->bound('dblog')) {
                app('dblog')->info('PayU success callback', ['txnid' => $txnid, 'status' => $status, 'payload' => $posted]);
            } else {
                Log::info('PayU success callback', ['txnid' => $txnid, 'status' => $status, 'payload' => $posted]);
            }
        } catch (\Throwable $e) {
            Log::error('DBLog (payu success) failed: '.$e->getMessage());
        }

        // Verify hash (reverse hash) — note: for responses, PayU hash verification must match spec
        $salt = env('PAYU_SALT');
        $key  = env('PAYU_KEY');

        // Reverse string: salt|status|||||||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key  :contentReference[oaicite:2]{index=2}
        $reverseHashString = $salt . '|' . ($posted['status'] ?? '') . '|||||||||||' .
                             ($posted['udf5'] ?? '') . '|' .
                             ($posted['udf4'] ?? '') . '|' .
                             ($posted['udf3'] ?? '') . '|' .
                             ($posted['udf2'] ?? '') . '|' .
                             ($posted['udf1'] ?? '') . '|' .
                             ($posted['email'] ?? '') . '|' .
                             ($posted['firstname'] ?? '') . '|' .
                             ($posted['productinfo'] ?? '') . '|' .
                             ($posted['amount'] ?? '') . '|' .
                             ($posted['txnid'] ?? '') . '|' .
                             $key;

        $calculatedHash = hash('sha512', $reverseHashString);
        $receivedHash   = $posted['hash'] ?? '';

        if (! hash_equals($calculatedHash, $receivedHash)) {
            Log::warning('PayU hash mismatch on success', ['txnid' => $txnid, 'calc' => $calculatedHash, 'recv' => $receivedHash]);
            return redirect()->route('checkout.failure')->with('error', 'Payment verification failed.');
        }

        // Payment is successful & verified — generate receipt, email, show thank you
        $checkout = session()->get('payu.checkout.' . $txnid, []);
        $email    = $posted['email']      ?? ($checkout['email'] ?? null);
        $name     = $posted['firstname']  ?? ($checkout['firstname'] ?? null);
        $phone    = $posted['phone']      ?? ($checkout['phone'] ?? null);
        $amount   = $posted['amount']     ?? ($checkout['amount'] ?? null);
        $course   = $posted['udf1']       ?? ($checkout['course'] ?? null);

        $receiptData = [
            'txnid'   => $txnid,
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'amount'  => $amount,
            'course'  => $this->titleFromSlug($course),
            'status'  => $posted['status'] ?? 'success',
            'date'    => now()->toDateTimeString(),
            'payu_response' => $posted,
        ];

        $pdf = PDF::loadView('checkout.receipt_pdf', $receiptData);
        $pdfPath = "public/receipts/{$txnid}.pdf";
        Storage::put($pdfPath, $pdf->output());

        $publicUrl = Storage::url("receipts/{$txnid}.pdf");

        try {
            Mail::to($email)->send(new PurchaseReceiptMail($receiptData, storage_path('app/' . $pdfPath)));
        } catch (\Throwable $e) {
            Log::error('Mail send failed: ' . $e->getMessage());
        }

        try {
            if (app()->bound('dblog')) {
                app('dblog')->info('Payment success processed', ['txnid' => $txnid, 'email' => $email, 'course' => $course, 'amount' => $amount]);
            } else {
                Log::info('Payment success processed', ['txnid' => $txnid, 'email' => $email, 'course' => $course, 'amount' => $amount]);
            }
        } catch (\Throwable $e) {
            Log::error('DBLog (payment success) failed: ' . $e->getMessage());
        }

        // Notify admin
        try {
            Mail::to(env('CONTACT_EMAIL'))->send(new PurchaseReceiptMail($receiptData, storage_path('app/' . $pdfPath), true));
        } catch (\Throwable $e) {
            Log::error('Admin notification email failed: ' . $e->getMessage());
        }

        session()->forget('payu.checkout.' . $txnid);

        return view('checkout.thankyou', [
            'receipt_url' => $publicUrl,
            'receipt'     => $receiptData,
        ]);
    }

    /**
     * Failure URL (furl)
     */
    public function failure(Request $request)
    {
        // Collect whatever PayU sent (POST body or query params)
        $posted = $request->all();
        $txnid  = $posted['txnid'] ?? null;

        // Log the failure callback (safe even if empty payload)
        try {
            if (app()->bound('dblog')) {
                app('dblog')->info('PayU failure callback', ['txnid' => $txnid, 'method' => $request->method(), 'payload' => $posted]);
            } else {
                \Log::info('PayU failure callback', ['txnid' => $txnid, 'method' => $request->method(), 'payload' => $posted]);
            }
        } catch (\Throwable $e) {
            \Log::error('DBLog (payu failure) failed: '.$e->getMessage());
        }

        // If PayU sent a txnid or other payload, pass it to the failure view
        if ($txnid || count($posted) > 0) {
            return view('checkout.failure', ['payload' => $posted]);
        }

        // No payload — show a friendly fallback page with next steps
        return view('checkout.failure', [
            'payload' => [],
            'message' => 'Payment was not completed. If you were charged, please contact support at '.env('CONTACT_EMAIL').'. You can retry the payment from the course page.',
        ]);
    }

    /**
     * Server-to-server notification (notify_url) — optional
     */
    public function notify(Request $request)
    {
        $posted = $request->all();
        $txnid  = $posted['txnid'] ?? null;

        try {
            if (app()->bound('dblog')) {
                app('dblog')->info('PayU notify received', ['txnid' => $txnid, 'payload' => $posted]);
            } else {
                Log::info('PayU notify received', ['txnid' => $txnid, 'payload' => $posted]);
            }
        } catch (\Throwable $e) {
            Log::error('DBLog (payu notify) failed: ' . $e->getMessage());
        }

        return response('ok', 200);
    }

    private function titleFromSlug(string $slug): string
    {
        return match ($slug) {
            'forex-mastery'       => 'Forex Mastery',
            'price-action'        => 'Price Action & Market Structure',
            'intraday-swing'      => 'Intraday & Swing Trading',
            'advanced-psychology' => 'Advanced Trading Psychology',
            default               => ucwords(str_replace(['-', '_'], ' ', $slug)),
        };
    }
}
