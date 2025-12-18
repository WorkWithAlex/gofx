@extends('layouts.app')

@section('title', ($guide->title ?? 'Guide') . ' — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <article class="bg-slate-900/40 rounded-2xl p-10">
      <div class="mb-4 text-slate-400 text-sm">
        <a href="{{ route('guides.index') }}" class="underline">Guides</a> &middot;
        <span class="ml-2">{{ $guide->published_at ? $guide->published_at->format('F j, Y') : '' }}</span>
      </div>

      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ $guide->title }}</h1>
      <div class="text-slate-300 mb-6">{{ $guide->excerpt }}</div>

      <div class="prose prose-invert max-w-none text-slate-100">
        {!! $guide->body ?? '<p>No content available yet.</p>' !!}
      </div>
    </article>
  </div>
</section>
@endsection
