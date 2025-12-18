@extends('layouts.app')

@section('title','Payment Failed — GOFX')

@section('content')
<div class="pt-24 pb-16">
  <div class="max-w-3xl mx-auto px-6">
    <div class="bg-black/40 p-8 rounded-lg text-center">
      <h1 class="text-3xl font-bold text-red-400">Payment failed or was cancelled</h1>

      @if(!empty($message))
        <p class="mt-4 text-slate-300">{!! $message !!}</p>
      @else
        <p class="mt-4 text-slate-300">If you were charged, please contact support at <strong>{{ env('CONTACT_EMAIL') }}</strong> with your transaction details.</p>
      @endif

      @if(!empty($payload))
        <div class="mt-4 text-left bg-black/30 p-3 rounded text-sm text-slate-300">
          <strong>Debug info (PayU):</strong>
          <pre class="whitespace-pre-wrap text-xs">{{ json_encode($payload, JSON_PRETTY_PRINT) }}</pre>
        </div>
      @endif

      <div class="mt-6">
        <a href="{{ url()->previous() }}" class="px-6 py-3 rounded-md border border-white/10">Try Again</a>
        <a href="{{ url('/contact') }}" class="ml-3 px-6 py-3 rounded-md border border-white/10">Contact Support</a>
      </div>
    </div>
  </div>
</div>
@endsection
