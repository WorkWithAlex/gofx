@extends('layouts.app')
@section('title','Library — GOFX')
@section('content')
<section class="py-20">
  <div class="max-w-6xl mx-auto px-6">
    <h1 class="text-4xl font-extrabold text-white mb-6">Library</h1>

    <div class="grid md:grid-cols-3 gap-6">
      @foreach($items as $it)
        <div class="bg-slate-800/30 p-6 rounded-2xl">
          <h3 class="text-white font-semibold mb-2">{{ $it->title }}</h3>
          <p class="text-slate-300 mb-4">{{ $it->summary }}</p>
          @if($it->file_path)
            <a href="{{ asset($it->file_path) }}" target="_blank" class="text-slate-200 font-semibold">Download →</a>
          @elseif($it->url)
            <a href="{{ $it->url }}" target="_blank" class="text-slate-200 font-semibold">Open →</a>
          @endif
        </div>
      @endforeach
    </div>

    <div class="mt-8">{{ $items->links() }}</div>
  </div>
</section>
@endsection
