@extends('layouts.app')

@section('title', 'Courses — GOFX')

@section('content')
<div class="pt-24 pb-16">
  <div class="max-w-5xl mx-auto px-6">
    <h1 class="text-3xl font-bold">All Courses</h1>
    <div class="mt-6 grid md:grid-cols-2 gap-6 ">
      @foreach($courses as $c)
        <div class='block p-5 bg-black/40 rounded-2xl border border-white/5 hover:scale-[1.01] transition glass-neon-blue'>
          <img src="{{$c['thumbnail']}}" alt="{{ $c['title'] }} Thumbnail" class="w-full mb-6 h-40 object-cover rounded-md border border-white/10">
          <h3 class="text-xl font-semibold text-white">{{ $c['title'] }}</h3>
          <p class="text-slate-300 mt-2">Open the course page to view curriculum and enroll.</p>
          <a class="mt-4 inline-block" href="{{ route('courses.show', $c['slug']) }}">
            <button class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
              View Details
            </button>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
