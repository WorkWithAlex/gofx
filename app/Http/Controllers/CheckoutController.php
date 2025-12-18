<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PDF;
use App\Mail\PurchaseReceiptMail;

class CheckoutController extends Controller
{
    protected $payuUrlSandbox = 'https://test.payu.in/_payment';
    protected $payuUrlProd = 'https://secure.payu.in/_payment';

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'course' => 'required|string',
            'currency' => 'required|in:INR,USD',
        ]);

        // Course prices (adjust INR as needed)
        $prices = [
            'forex-mastery' => ['USD' => 20000, 'INR' => 20000],
            'price-action' => ['USD' => 20000, 'INR' => 20000],
            'intraday-swing' => ['USD' => 20000, 'INR' => 20000],
            // 'advanced-psychology' => ['USD' => 25000, 'INR' => 25000],
            'smart-money-concepts' => ['USD' => 25000, 'INR' => 25000],
        ];

        $slug = $request->input('course');

        if (! array_key_exists($slug, $prices)) {
            return back()->with('error', 'Invalid course selected.');
        }

        $currency = $request->input('currency', 'INR');
        $amount = $prices[$slug][$currency] ?? $prices[$slug]['USD'];

        if (! $amount) {
            return back()->with('error', 'Unable to determine course price.');
        }

        $key = config('app.payu_key') ?: env('PAYU_KEY');
        $salt = config('app.payu_salt') ?: env('PAYU_SALT');
        $mode = env('PAYU_MODE', 'sandbox');

        $txnid = 'gofx_' . Str::random(12) . time();
        $productinfo = "GOFX - " . $this->titleFromSlug($slug);
        $amountStr = number_format((float)$amount, 2, '.', '');

        $data = [
            'key' => $key,
            'txnid' => $txnid,
            'amount' => $amountStr,
            'productinfo' => $productinfo,
            'firstname' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'surl' => route('payment.success'),
            'furl' => route('payment.failure'),
            'service_provider' => 'payu_paisa',
            'udf1' => $slug,
            'udf2' => $currency,
            'currency' => $currency,
            'notify_url' => route('payment.notify'),
        ];

        // Correct hash generation (PayU Seamless)
        $hashString = $data['key'] . '|' .
              $data['txnid'] . '|' .
              $data['amount'] . '|' .
              $data['productinfo'] . '|' .
              $data['firstname'] . '|' .
              $data['email'] . '|' .
              $data['udf1'] . '|' .
              $data['udf2'] . '|' .
              '' . '|' . // udf3
              '' . '|' . // udf4
              '' . '|' . // udf5
              '' . '|' . // udf6
              '' . '|' . // udf7
              '' . '|' . // udf8
              '' . '|' . // udf9
              '' .       // udf10 — no trailing pipe
              '|' . $salt;

        $data['hash'] = strtolower(hash('sha512', $hashString));

        // Save to session
        session()->put('payu.checkout.' . $txnid, [
            'txnid' => $txnid,
            'course' => $slug,
            'currency' => $currency,
            'amount' => $amountStr,
            'firstname' => $data['firstname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);

        // Logging
        try {
            if (app()->bound('dblog')) {
                app('dblog')->info('Checkout initiated', ['txnid' => $txnid, 'course' => $slug, 'email' => $data['email']]);
            } else {
                \Log::info('Checkout initiated', ['txnid' => $txnid, 'course' => $slug, 'email' => $data['email']]);
            }
        } catch (\Throwable $e) {
            \Log::error('DBLog (checkout) failed: '.$e->getMessage());
        }

        $payuUrl = $mode === 'production' ? $this->payuUrlProd : $this->payuUrlSandbox;

        return view('checkout.redirect', [
            'payuUrl' => $payuUrl,
            'postData' => $data,
        ]);
    }


    public function success(Request $request)
    {
        $txnid = $request->input('txnid');
        $checkout = session()->get('payu.checkout.' . $txnid, []);

        $receiptData = [
            'txnid' => $txnid,
            'name' => $checkout['firstname'] ?? '',
            'email' => $checkout['email'] ?? '',
            'phone' => $checkout['phone'] ?? '',
            'amount' => $checkout['amount'] ?? '',
            'course' => $this->titleFromSlug($checkout['course'] ?? ''),
            'status' => $request->input('status', 'success'),
            'date' => now()->toDateTimeString(),
            'payu_response' => $request->all(),
        ];

        $pdf = PDF::loadView('checkout.receipt_pdf', $receiptData);
        $pdfPath = "public/receipts/{$txnid}.pdf";
        Storage::put($pdfPath, $pdf->output());
        $publicUrl = Storage::url("receipts/{$txnid}.pdf");

        try {
            Mail::to($receiptData['email'])->send(new PurchaseReceiptMail($receiptData, storage_path('app/' . $pdfPath)));
            Mail::to(env('CONTACT_EMAIL'))->send(new PurchaseReceiptMail($receiptData, storage_path('app/' . $pdfPath), true));
        } catch (\Throwable $e) {
            Log::error('Mail send failed: '.$e->getMessage());
        }

        session()->forget('payu.checkout.' . $txnid);
        return view('checkout.thankyou', [
            'receipt_url' => $publicUrl,
            'receipt' => $receiptData,
        ]);
    }

    public function failure(Request $request)
    {
        $txnid = $request->input('txnid', 'N/A');
        Log::warning('PayU payment failed', ['txnid' => $txnid, 'payload' => $request->all()]);

        return view('checkout.failure', [
            'payload' => $request->all(),
            'message' => 'Payment was not completed. If you were charged, please contact support at ' . env('CONTACT_EMAIL')
        ]);
    }

    public function notify(Request $request)
    {
        $txnid = $request->input('txnid');
        Log::info('PayU notify received', ['txnid' => $txnid, 'payload' => $request->all()]);
        return response('ok', 200);
    }

    private function titleFromSlug(string $slug): string
    {
        return match ($slug) {
            'forex-mastery' => 'Forex Mastery',
            'price-action' => 'Price Action & Market Structure',
            'intraday-swing' => 'Intraday & Swing Trading',
            'advanced-psychology' => 'Advanced Trading Psychology',
            default => ucwords(str_replace(['-', '_'], ' ', $slug)),
        };
    }
}