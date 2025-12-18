@extends('layouts.app')

@section('title','Thank You — GOFX')

@section('content')
<div class="pt-24 pb-16">
  <div class="max-w-3xl mx-auto px-6">
    <div class="bg-black/40 p-8 rounded-lg text-center">
      <h1 class="text-3xl font-bold">Thank you — your payment was successful</h1>
      <p class="mt-4 text-slate-300">Transaction ID: <strong>{{ $receipt['txnid'] }}</strong></p>
      <p class="mt-2 text-slate-300">Course: <strong>{{ $receipt['course'] }}</strong></p>
      <p class="mt-2 text-slate-300">Amount: <strong>{{ $receipt['amount'] }}</strong></p>

      <div class="mt-6">
        <a href="{{ $receipt_url }}" target="_blank" class="inline-block px-6 py-3 rounded-md font-semibold"
           style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
          Download Receipt (PDF)
        </a>
      </div>

      <p class="mt-4 text-xs text-slate-400">We have emailed the receipt to: <strong>{{ $receipt['email'] }}</strong></p>
    </div>
  </div>
</div>
@endsection
