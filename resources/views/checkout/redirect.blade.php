@extends('layouts.app')

@section('title','Redirecting to Payment')

@section('content')
<div class="pt-24 pb-16">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h2 class="text-2xl font-bold mb-4">Redirecting to secure payment...</h2>
    <p class="text-slate-300 mb-6">You will be redirected to the payment gateway. Please do not refresh the page.</p>

    <form id="payuForm" method="post" action="{{ $payuUrl }}">
      @foreach($postData as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
      @endforeach
      <noscript>
        <p>JavaScript is required to process the payment. Please enable JS or press Submit.</p>
        <button type="submit" class="px-4 py-2 rounded" style="background:linear-gradient(90deg,var(--accent2),var(--accent1));color:#000">Proceed</button>
      </noscript>
    </form>

    <script>
      (function(){
        setTimeout(function(){ document.getElementById('payuForm').submit(); }, 600);
      })();
    </script>
  </div>
</div>
@endsection
