@extends('layouts.app')

@section('title', 'Price Action & Market Structure — GOFX')

@section('content')
<section class="pt-24 pb-16">
  <div class="max-w-6xl mx-auto px-6">
    <!-- Hero -->
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold">Price Action / Market Structure</h1>
        <p class="mt-4 text-slate-300 max-w-xl">Master price structure — order blocks, liquidity, change of character, and how institutions shape every move.</p>

        <div class="mt-6 flex gap-3">
          <!-- Pay Now button that opens modal -->
          <button data-open-enroll="price-action-market-structure" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>
          <a href="#curriculum" class="inline-block px-6 py-3 rounded-md border border-white/10 text-slate-200">
            View Curriculum
          </a>
        </div>
      </div>

      <div class="bg-black/50 rounded-xl p-6 border border-white/5">
        <h4 class="text-white font-semibold">Who should join</h4>
        <p class="mt-3 text-slate-300">Traders who want to read price without over-relying on indicators and who want institutional-level edge in entries.</p>
      </div>
    </div>

    <!-- Curriculum -->
    <div id="curriculum" class="mt-12 space-y-6">
      <h3 class="text-2xl font-bold">Curriculum — Price Action & Market Structure</h3>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 1 — Market Structure Basics</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Support & resistance, trend identification</li>
          <li>Higher highs / lower lows, market phases</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 2 — Institutional Order Flow</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Order blocks, liquidity pools, inducements</li>
          <li>FVG (fair value gaps) and imbalances</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 3 — Structure Breaks & Re-tests</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Break of Structure (BoS) and Change of Character (ChoCh)</li>
          <li>Re-test mechanics and optimal entries</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 4 — Combining Context & Execution</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Confluence-based entries using structure + volume</li>
          <li>Stop placement, invalidation zones, trade management</li>
        </ul>
      </div>
    </div>

    <!-- FAQs & Testimonials -->
    <div class="mt-12 grid md:grid-cols-2 gap-8">
      <div>
        <h4 class="text-xl font-semibold">FAQs</h4>
        <div class="mt-4 space-y-3 text-slate-300">
          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Do I need indicators?</summary>
            <div class="mt-2">No. This course focuses on reading price structure. Indicators are taught only when they add value.</div>
          </details>

          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Is this advanced?</summary>
            <div class="mt-2">It’s suitable for intermediate traders or beginners willing to focus on price-action theory. Modules progress from basic to advanced.</div>
          </details>
        </div>
      </div>

      <div>
        <h4 class="text-xl font-semibold">Testimonials</h4>
        <div class="mt-4 space-y-3">
          <blockquote class="bg-black/40 p-4 rounded">
            <p class="text-slate-200">"After this course, structure-based entries improved my win-rate and reduced overtrading." — <strong>Vikram P.</strong></p>
          </blockquote>
        </div>
      </div>
    </div>

    <!-- Bottom CTA -->
    <div class="mt-12 py-8 px-6 bg-black/40 rounded-lg flex items-center justify-between">
      <div>
        <h5 class="text-lg font-semibold">Start reading price the right way</h5>
        <p class="text-slate-300">Reserve a seat now — live cohorts are limited.</p>
      </div>
      <div>
        <button data-open-enroll="price-action-market-structure" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
          Pay Now to Enroll
        </button>
      </div>
    </div>

  </div>
</section>
@endsection

@include('partials.checkout.enroll-modal', ['course' => 'price-action-market-structure'])