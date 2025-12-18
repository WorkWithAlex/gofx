@extends('layouts.app')
@section('title','Tools & Calculators — GOFX')
@section('content')
<section class="py-20">
  <div class="max-w-6xl mx-auto px-6">
    <h1 class="text-4xl font-extrabold text-white mb-6">Tools & Calculators</h1>

    <div class="grid md:grid-cols-3 gap-6">
      @foreach($tools as $t)
        <div class="bg-slate-800/30 p-6 rounded-2xl">
          <h3 class="text-white font-semibold mb-2"><a href="{{ route('tools.show', $t->slug) }}">{{ $t->title }}</a></h3>
          <p class="text-slate-300 mb-4">{{ $t->summary }}</p>
          <a href="{{ route('tools.show', $t->slug) }}" class="text-slate-200 font-semibold">Open tool →</a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
