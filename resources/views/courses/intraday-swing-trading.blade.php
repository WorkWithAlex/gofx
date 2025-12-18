@extends('layouts.app')

@section('title', 'Intraday & Swing Trading — GOFX')

@section('content')
<section class="pt-24 pb-16">
  <div class="max-w-6xl mx-auto px-6">

    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold">Intraday & Swing Trading</h1>
        <p class="mt-4 text-slate-300 max-w-xl">Learn separate, proven workflows for intraday scalping and multi-day swing trades — trade frequency, management, and edge.</p>

        <div class="mt-6 flex gap-3">
          <button data-open-enroll="intraday-swing-trading" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>
          <a href="#curriculum" class="inline-block px-6 py-3 rounded-md border border-white/10 text-slate-200">
            View Curriculum
          </a>
        </div>
      </div>

      <div class="bg-black/50 rounded-xl p-6 border border-white/5">
        <h4 class="text-white font-semibold">Who this helps</h4>
        <p class="mt-3 text-slate-300">Traders wanting two distinct playbooks: quick intraday moves and structured swing entries for larger trends.</p>
      </div>
    </div>

    <div id="curriculum" class="mt-12 space-y-6">
      <h3 class="text-2xl font-bold">Curriculum — Intraday & Swing Trading</h3>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Part A — Intraday / Scalping</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Pre-market checklist and watchlist creation</li>
          <li>Quick setups: momentum, range breaks</li>
          <li>Micro risk management and quick exit rules</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Part B — Swing Trading</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Trend identification across multiple timeframes</li>
          <li>Using order blocks & structure for swing entries</li>
          <li>Holding rules, partial profit-taking & trailing stops</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Part C — Tools & Examples</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Indicator combos for intraday (VWAP + EMA + Volume)</li>
          <li>Backtesting basics and simple edge measurement</li>
          <li>Trade journaling templates</li>
        </ul>
      </div>
    </div>

    <!-- FAQs and Testimonials -->
    <div class="mt-12 grid md:grid-cols-2 gap-8">
      <div>
        <h4 class="text-xl font-semibold">FAQs</h4>
        <div class="mt-4 space-y-3 text-slate-300">
          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Can I take both intraday & swing approaches?</summary>
            <div class="mt-2">Yes — the course separates playbooks so you can adopt one or both depending on your time availability.</div>
          </details>

          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Do we look at live examples?</summary>
            <div class="mt-2">Yes — live market examples are a core part of the sessions.</div>
          </details>
        </div>
      </div>

      <div>
        <h4 class="text-xl font-semibold">Testimonials</h4>
        <div class="mt-4 space-y-3">
          <blockquote class="bg-black/40 p-4 rounded">
            <p class="text-slate-200">"The swing rules saved my capital — clearly defined holds and exits." — <strong>Neha D.</strong></p>
          </blockquote>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="mt-12 py-8 px-6 bg-black/40 rounded-lg flex items-center justify-between">
      <div>
        <h5 class="text-lg font-semibold">Choose your playbook</h5>
        <p class="text-slate-300">One course, two practical workflows. Reserve your seat now.</p>
      </div>
      <div>
          <button data-open-enroll="intraday-swing-trading" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>
      </div>
    </div>

  </div>
</section>
@endsection

@include('partials.checkout.enroll-modal', ['course' => 'intraday-swing-trading'])