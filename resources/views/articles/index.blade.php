@extends('layouts.app')

@section('title', 'Articles — Learn Hub — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-6xl mx-auto px-6">
    <h1 class="text-4xl font-extrabold text-white mb-6">Articles</h1>

    <div class="grid md:grid-cols-3 gap-6">
      @foreach($articles as $a)
        <article class="bg-slate-800/30 rounded-2xl p-6">
          <h3 class="text-white text-xl font-semibold mb-2">
            <a href="{{ route('articles.show', $a->slug) }}">{{ $a->title }}</a>
          </h3>
          <p class="text-slate-300 mb-4">{{ $a->excerpt }}</p>
          <div class="flex items-center justify-between">
            <div class="text-slate-400 text-sm">By {{ $a->author ?? 'GOFX' }} • {{ $a->published_at ? $a->published_at->diffForHumans() : '' }}</div>
            <a href="{{ route('articles.show', $a->slug) }}" class="text-sm font-semibold text-slate-200">Read →</a>
          </div>
        </article>
      @endforeach
    </div>

    <div class="mt-8">
      {{ $articles->links() }}
    </div>
  </div>
</section>
@endsection
