@extends('layouts.app')

@section('title', 'Smart Money Concepts — GOFX')

@section('content')
<section class="pt-24 pb-16">
  <div class="max-w-6xl mx-auto px-6 min-h-[40vh]">

    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold">{{ $price }} INR Test Product</h1>
        <p class="mt-4 text-slate-300 max-w-xl">
            This is a test product for verifying the Razorpay payment integration. Do not use this for real purchases.
        </p>

        <div class="mt-6 flex gap-3">
          <!-- Pay Now button that opens modal -->
          <button data-open-enroll="test" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>
        </div>
      </div>

      <div class="bg-black/50 rounded-xl p-6 border border-white/5">
        <h4 class="text-white font-semibold">Who can use this test page</h4>
        <p class="mt-3 text-slate-300">
            This test page is for verifying the Razorpay payment integration. It is intended for developers and testers to ensure the payment flow works correctly. Do not share payment details made here. And doe not use this page for real purchases.
        </p>
      </div>
    </div>
  </div>

  @include('partials.checkout.enroll-modal', ['course' => 'test'])

</section>
@endsection
