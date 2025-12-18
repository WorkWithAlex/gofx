@extends('layouts.app')

@section('title', 'Strategies — Learn Hub — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-6xl mx-auto px-6">
    <div class="mb-8">
      <h1 class="text-4xl font-extrabold text-white">Strategies</h1>
      <p class="text-slate-300 mt-2 max-w-2xl">Detailed strategy write-ups, examples and performance notes to help you execute with discipline.</p>
    </div>

    @if($strategies->count())
      <div class="grid md:grid-cols-3 gap-6">
        @foreach($strategies as $s)
          <article class="bg-slate-800/30 rounded-2xl p-6">
            <h3 class="text-white text-xl font-semibold mb-2">
              <a href="{{ route('strategies.show', $s->slug) }}">{{ $s->title }}</a>
            </h3>

            <p class="text-slate-300 mb-4">{{ $s->excerpt }}</p>

            <div class="flex items-center justify-between">
              <div class="text-slate-400 text-sm">By {{ $s->author ?? 'GOFX' }}</div>
              <a href="{{ route('strategies.show', $s->slug) }}" class="text-sm font-semibold text-slate-200">Read →</a>
            </div>
          </article>
        @endforeach
      </div>

      <div class="mt-8">
        {{ $strategies->links() }}
      </div>
    @else
      <div class="bg-slate-900/40 rounded-2xl p-8 text-slate-300">
        No strategies found yet. Check back soon.
      </div>
    @endif
  </div>
</section>
@endsection
