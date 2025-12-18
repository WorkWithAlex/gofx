@extends('layouts.app')

@section('title', $article->title.' — Articles — GOFX')
@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <article class="bg-slate-900/40 rounded-2xl p-10">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ $article->title }}</h1>
      <div class="text-slate-400 mb-6">By {{ $article->author ?? 'GOFX' }} • {{ $article->published_at ? $article->published_at->format('F j, Y') : '' }}</div>

      <div class="prose prose-invert max-w-none text-slate-100">
        {!! $article->body !!}
      </div>
    </article>
  </div>
</section>
@endsection
