<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\Mail\PurchaseReceiptMail;
use Illuminate\Support\Str;

class RazorpayCheckoutController extends Controller
{
    protected Api $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('razorpay.key'),
            config('razorpay.secret')
        );
    }

    /**
     * Create Razorpay Order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'course' => 'required|string',
        ]);

        // INR prices only (USD is display-only)
        $prices = [
            'forex-mastery' => 45000,
            'price-action' => 22500,
            'intraday-swing' => 36000,
            // 'advanced-psychology' => 45000,
            'smart-money-concepts' => 25500,
            'test' => $request->has('test_price') ? (int)$request->test_price : 1,
        ];

        $slug = $request->course;

        if (!isset($prices[$slug])) {
            return response()->json(['error' => 'Invalid course'], 422);
        }

        $amountInRupees = $prices[$slug];
        $amountInPaise = $amountInRupees * 100;

        $order = $this->razorpay->order->create([
            'receipt' => 'gofx_' . Str::random(10),
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        session()->put('razorpay.checkout.' . $order['id'], [
            'order_id' => $order['id'],
            'course' => $slug,
            'amount' => $amountInRupees,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $this->log('Razorpay order created', [
            'order_id' => $order['id'] ?? null,
            'amount'   => $order['amount'] ?? null,
            'currency' => $order['currency'] ?? null,
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'key' => config('razorpay.key'),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'course' => $this->titleFromSlug($slug),
        ]);
    }

    /**
     * Verify payment signature
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $session = session()->get('razorpay.checkout.' . $request->razorpay_order_id);

        if (!$session) {
            abort(419, 'Session expired');
        }

        $generatedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            config('razorpay.secret')
        );

        if (!hash_equals($generatedSignature, $request->razorpay_signature)) {
            $this->log('Razorpay signature mismatch', $request->all());
            return redirect()->route('checkout.failure');
        }

        // Receipt
        $receiptData = [
            'txnid' => $request->razorpay_payment_id,
            'name' => $session['name'],
            'email' => $session['email'],
            'phone' => $session['phone'],
            'amount' => $session['amount'],
            'course' => $this->titleFromSlug($session['course']),
            'gateway' => 'Razorpay',
            'status'  => 'success',
            'date' => now()->toDateTimeString(),
        ];

        $pdf = PDF::loadView('checkout.receipt_pdf', $receiptData);
        $pdfPath = "public/receipts/{$request->razorpay_payment_id}.pdf";
        Storage::disk('public')->put(
            "receipts/{$request->razorpay_payment_id}.pdf",
            $pdf->output()
        );

        $receiptUrl = asset(
            'storage/receipts/' . $request->razorpay_payment_id . '.pdf'
        );

        try {
            Mail::to($session['email'])->send(
                new PurchaseReceiptMail($receiptData, storage_path('app/' . $pdfPath))
            );
            Mail::to(env('CONTACT_EMAIL'))->send(
                new PurchaseReceiptMail($receiptData, storage_path('app/' . $pdfPath), true)
            );
        } catch (\Throwable $e) {
            Log::error('Receipt email failed: ' . $e->getMessage());
        }

        session()->forget('razorpay.checkout.' . $request->razorpay_order_id);
        session()->put('checkout.receipt', $receiptData);
        session()->put('checkout.receipt_url', $receiptUrl);

        $this->log('Razorpay payment success', $receiptData);

        return redirect()->route('checkout.thankyou');
    }

    private function titleFromSlug(string $slug): string
    {
        return match ($slug) {
            'forex-mastery' => 'Forex Mastery',
            'price-action' => 'Price Action & Market Structure',
            'intraday-swing' => 'Intraday & Swing Trading',
            // 'advanced-psychology' => 'Advanced Trading Psychology',
            'smart-money-concepts' => 'Smart Money Concepts',
            default => ucfirst(str_replace('-', ' ', $slug)),
        };
    }

    private function log(string $message, array $context = [])
    {
        try {
            if (app()->bound('dblog')) {
                app('dblog')->info($message, $context);
            } else {
                Log::info($message, $context);
            }
        } catch (\Throwable $e) {
            Log::error('DBLog failed: ' . $e->getMessage());
        }
    }
}
