@extends('layouts.app')

@section('title', ($strategy->title ?? 'Strategy') . ' — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <article class="bg-slate-900/40 rounded-2xl p-10">
      <div class="mb-4 text-slate-400 text-sm">
        <a href="{{ route('strategies.index') }}" class="underline">Strategies</a> &middot;
        <span class="ml-2">{{ $strategy->published_at ? $strategy->published_at->format('F j, Y') : '' }}</span>
      </div>

      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ $strategy->title }}</h1>
      <div class="text-slate-300 mb-6">{{ $strategy->excerpt }}</div>

      <div class="prose prose-invert max-w-none text-slate-100">
        {!! $strategy->body ?? '<p>No content available yet.</p>' !!}
      </div>
    </article>
  </div>
</section>
@endsection
