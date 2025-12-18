@extends('layouts.app')
@section('title', $tool->title.' — Tools — GOFX')
@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-10">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4">{{ $tool->title }}</h1>
      <p class="text-slate-300 mb-6">{{ $tool->summary }}</p>

      <div class="prose prose-invert text-slate-100">
        {!! $tool->body ?? '<p>Tool description and controls will be implemented here.</p>' !!}
      </div>

      {{-- Placeholder area for calculator UI (we will implement calculators next) --}}
      <div class="mt-8">
        <div class="bg-slate-800/40 p-6 rounded-lg">
          <h4 class="text-white font-semibold mb-3">Calculator / Tool UI</h4>
          <p class="text-slate-300">We will add interactive calculator controls here in the next step. For now this page explains the tool and provides examples.</p>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
