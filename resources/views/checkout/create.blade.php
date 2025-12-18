@extends('layouts.app')

@section('title', 'Checkout — ' . ($courseTitle ?? 'Course'))

@section('content')
<div class="pt-24 pb-16">
  <div class="max-w-3xl mx-auto px-6">
    <h1 class="text-3xl font-bold">Checkout — {{ $courseTitle }}</h1>
    <p class="mt-3 text-slate-300">You selected: <strong>{{ $course }}</strong></p>

    <div class="mt-6">
      <button id="payBtn" class="px-6 py-3 rounded-md font-semibold"
        style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
        Create Payment Session
      </button>
    </div>

    <script>
      document.getElementById('payBtn').addEventListener('click', async () => {
        const res = await fetch("{{ route('checkout.createSession') }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({})
        });
        const data = await res.json();
        if(data.payment_url){
          // In production you'd redirect to the payment gateway or open the gateway modal
          window.location.href = data.payment_url;
        } else {
          alert('Could not create payment session. Check logs.');
        }
      });
    </script>
  </div>
</div>
@endsection
