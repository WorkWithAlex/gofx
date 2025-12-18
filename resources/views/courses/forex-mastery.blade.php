@extends('layouts.app')

@section('title', 'Forex Mastery — GOFX')

@section('content')
<section class="relative pt-24 pb-16">
  <div class="max-w-6xl mx-auto px-6">
    <!-- Hero -->
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Forex Mastery</h1>
        <p class="mt-4 text-slate-300 max-w-xl">
          A complete, hands-on program for traders who want deep practical knowledge of forex markets — pips, lots,
          leverage, risk, and institutional market structure. Learn LIVE strategies you can trade immediately.
        </p>

        <ul class="mt-6 space-y-3 text-slate-300">
          <li>✅ 8 live sessions (2 hours each) — weekend friendly</li>
          <li>✅ Lifetime community access & WhatsApp trading room</li>
          <li>✅ Practical assignments, charts, and live trade walkthroughs</li>
        </ul>

        <div class="mt-6 flex gap-3">
          <button data-open-enroll="forex-mastery" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>

          <a href="#curriculum" class="inline-block px-6 py-3 rounded-md border border-white/10 text-slate-200">
            View Curriculum
          </a>
        </div>
      </div>

      <div class="bg-black/50 rounded-xl p-6 border border-white/5">
        <h4 class="text-white font-semibold">Who this is for</h4>
        <ul class="mt-4 text-slate-300 space-y-2">
          <li>• Beginners who want a structured forex start</li>
          <li>• Intermediate traders wanting institutional concepts</li>
          <li>• Anyone who wants replicable, actionable trade setups</li>
        </ul>

        <div class="mt-6">
          <h5 class="text-white font-semibold">Course Snapshot</h5>
          <div class="mt-3 grid grid-cols-2 gap-3 text-sm text-slate-300">
            <div>Duration</div><div>8 sessions (16 hrs)</div>
            <div>Format</div><div>Live + Notes + Community</div>
            <div>Access</div><div>Lifetime (community)</div>
            <div>Price</div><div>Set on checkout</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Benefits -->
    <div class="mt-12 grid md:grid-cols-3 gap-6">
      <div class="p-6 bg-[#050613]/60 rounded-xl border border-white/5">
        <h4 class="text-white font-semibold">Real Market Understanding</h4>
        <p class="mt-2 text-slate-300">Understand pips, lots, leverage and how market participants behave in forex.</p>
      </div>
      <div class="p-6 bg-[#050613]/60 rounded-xl border border-white/5">
        <h4 class="text-white font-semibold">Live Execution</h4>
        <p class="mt-2 text-slate-300">Live trade walkthroughs — entries, exits, risk management and post-trade review.</p>
      </div>
      <div class="p-6 bg-[#050613]/60 rounded-xl border border-white/5">
        <h4 class="text-white font-semibold">Tools & Templates</h4>
        <p class="mt-2 text-slate-300">Watchlists, pre-open checklists, and quick decision templates for the trading day.</p>
      </div>
    </div>

    <!-- Curriculum -->
    <div id="curriculum" class="mt-12">
      <h3 class="text-2xl font-bold">Curriculum — Forex Mastery</h3>
      <p class="mt-2 text-slate-300 max-w-2xl">A practical syllabus built from foundational concepts to advanced execution.</p>

      <div class="mt-6 space-y-6">
        <div class="bg-black/50 p-5 rounded-lg border border-white/5">
          <h4 class="text-lg font-semibold">Module 1 — Markets & Charts (Foundations)</h4>
          <ul class="mt-3 text-slate-300 list-disc pl-5 space-y-1">
            <li>Market participants & market structure</li>
            <li>Candlesticks, OHLC, timeframes</li>
            <li>How forex differs: pips, lots, leverage, margin</li>
            <li>Setting up TradingView and watchlists</li>
          </ul>
        </div>

        <div class="bg-black/50 p-5 rounded-lg border border-white/5">
          <h4 class="text-lg font-semibold">Module 2 — Order Types & Execution</h4>
          <ul class="mt-3 text-slate-300 list-disc pl-5 space-y-1">
            <li>Order types (market, limit, stop, OCO)</li>
            <li>Pre-open analysis, bid-ask behavior, slippage</li>
            <li>Position sizing & stop placement (risk per trade)</li>
          </ul>
        </div>

        <div class="bg-black/50 p-5 rounded-lg border border-white/5">
          <h4 class="text-lg font-semibold">Module 3 — Price Structure & Institutional Concepts</h4>
          <ul class="mt-3 text-slate-300 list-disc pl-5 space-y-1">
            <li>Support & resistance, trendlines, change of character</li>
            <li>Order blocks, liquidity pools, imbalance (FVG)</li>
            <li>Break of Structure (BoS) and Market Structure Breaks (MSB)</li>
          </ul>
        </div>

        <div class="bg-black/50 p-5 rounded-lg border border-white/5">
          <h4 class="text-lg font-semibold">Module 4 — Indicators & Tools</h4>
          <ul class="mt-3 text-slate-300 list-disc pl-5 space-y-1">
            <li>VWAP, EMA, SMA, ATR, Bollinger Bands</li>
            <li>Volume analysis, pivots, Stochastic RSI</li>
            <li>How & when to use indicators vs price action</li>
          </ul>
        </div>

        <div class="bg-black/50 p-5 rounded-lg border border-white/5">
          <h4 class="text-lg font-semibold">Module 5 — Strategy & Execution</h4>
          <ul class="mt-3 text-slate-300 list-disc pl-5 space-y-1">
            <li>Intraday setups for forex pairs</li>
            <li>Swing entries using structure & order blocks</li>
            <li>Trade journaling and post-trade analysis</li>
          </ul>
        </div>

        <div class="bg-black/50 p-5 rounded-lg border border-white/5">
          <h4 class="text-lg font-semibold">Module 6 — Risk, Psychology & Live Trading</h4>
          <ul class="mt-3 text-slate-300 list-disc pl-5 space-y-1">
            <li>Risk management frameworks and position sizing</li>
            <li>Trading psychology — loss acceptance & discipline</li>
            <li>Live trade session + Q&A</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- FAQs & Testimonials -->
    <div class="mt-12 grid md:grid-cols-2 gap-8">
      <div>
        <h4 class="text-xl font-semibold">FAQs</h4>
        <div class="mt-4 space-y-3 text-slate-300">
          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold cursor-pointer">Are recordings provided?</summary>
            <div class="mt-2 text-slate-300">No. Our live mentorship sessions are not recorded — this creates accountability and live interaction. Notes and materials are shared.</div>
          </details>

          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold cursor-pointer">What is the refund policy?</summary>
            <div class="mt-2 text-slate-300">All sales are final per GOFX refund & cancellation policy. See site Terms for details.</div>
          </details>

          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold cursor-pointer">Which platforms are used?</summary>
            <div class="mt-2 text-slate-300">TradingView for charting; WhatsApp for community; Zoom for live sessions.</div>
          </details>
        </div>
      </div>

      <div>
        <h4 class="text-xl font-semibold">Testimonials</h4>
        <div class="mt-4 space-y-4">
          <blockquote class="bg-black/40 p-4 rounded">
            <p class="text-slate-200">"Practical, no-fluff sessions. I could apply the watchlist & risk rules from Week 1." — <strong>Rohit S.</strong></p>
          </blockquote>

          <blockquote class="bg-black/40 p-4 rounded">
            <p class="text-slate-200">"Tehseen explains institutional concepts simply — order blocks and liquidity made sense finally." — <strong>Ayesha K.</strong></p>
          </blockquote>
        </div>
      </div>
    </div>

    <!-- Bottom CTA -->
    <div class="mt-12 py-8 px-6 bg-black/40 rounded-lg flex items-center justify-between">
      <div>
        <h5 class="text-lg font-semibold">Ready to join?</h5>
        <p class="text-slate-300">Secure your seat and get instant access to community resources after checkout.</p>
      </div>
      <div>
          <button data-open-enroll="forex-mastery" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>
      </div>
    </div>
  </div>
</section>
@endsection

@include('partials.checkout.enroll-modal', ['course' => 'forex-mastery'])