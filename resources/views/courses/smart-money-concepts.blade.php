@extends('layouts.app')

@section('title', 'Smart Money Concepts — GOFX')

@section('content')
<section class="pt-24 pb-16">
  <div class="max-w-6xl mx-auto px-6">

    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold">Smart Money Concepts</h1>
        <p class="mt-4 text-slate-300 max-w-xl">Learn how institutional players move markets. Master order blocks, liquidity, fair value gaps, BOS, CHoCH and high-probability institutional entries.</p>

        <div class="mt-6 flex gap-3">
          <!-- Pay Now button that opens modal -->
          <button data-open-enroll="smart-money-concepts" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>
          <a href="#curriculum" class="inline-block px-6 py-3 rounded-md border border-white/10 text-slate-200">
            View Curriculum
          </a>
        </div>
      </div>

      <div class="bg-black/50 rounded-xl p-6 border border-white/5">
        <h4 class="text-white font-semibold">Why this course matters</h4>
        <p class="mt-3 text-slate-300">Institutional footprints determine real moves. Stop trading noise and learn to follow the flow of smart money for better entries and fewer false breakouts.</p>
      </div>
    </div>

    <div id="curriculum" class="mt-12 space-y-6">
      <h3 class="text-2xl font-bold">Curriculum — Smart Money Concepts</h3>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 1 — Market Structure</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Trend context: identifying bias on multiple timeframes</li>
          <li>Break of Structure (BOS) and Change of Character (CHoCH)</li>
          <li>How to interpret structure shifts for trade direction</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 2 — Liquidity</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Buy-side vs sell-side liquidity and where it pools</li>
          <li>Equal highs / equal lows and liquidity sweeps</li>
          <li>Recognizing stop-hunts and engineered moves</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 3 — Order Blocks (OB)</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Defining bullish and bearish order blocks</li>
          <li>Institutional candles and POI (point of interest)</li>
          <li>Entry refinement and confirmation techniques</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 4 — Fair Value Gaps (FVG) & Imbalances</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Identifying imbalances and why they matter</li>
          <li>How FVGs get filled and using them for entries</li>
          <li>Combining FVG with OB and structure for confluence</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 5 — Supply & Demand / Premium & Discount</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Premium and discount zones from an institutional view</li>
          <li>Drawing high-value S/D zones and prioritizing POIs</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 6 — Inducement & Traps</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Recognizing inducement patterns and fake breakouts</li>
          <li>How institutions induce retail to create liquidity</li>
          <li>Where to expect reversals and how to protect entries</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 7 — Institutional Entry Framework</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Combining BOS, OB, FVG and liquidity for entries</li>
          <li>Risk management rules tailored to SMC setups</li>
          <li>Trade management and scaling in/out techniques</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 8 — Practical Sessions & Case Studies</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Live chart walkthroughs on multiple timeframes</li>
          <li>Case studies: real trades annotated and reviewed</li>
          <li>Homework, chart tasks and community review</li>
        </ul>
      </div>
    </div>

    <!-- FAQs & Testimonials -->
    <div class="mt-12 grid md:grid-cols-2 gap-8">
      <div>
        <h4 class="text-xl font-semibold">FAQs</h4>
        <div class="mt-4 space-y-3 text-slate-300">
          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Is this course technical or practical?</summary>
            <div class="mt-2">It is heavily practical. Modules are theory-light and focus on live chart work, rules, and habit formation to apply SMC in real markets.</div>
          </details>

          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Do I need prior experience?</summary>
            <div class="mt-2">Basic understanding of price action and timeframes helps. This course pairs well with the Price Action & Market Structure course.</div>
          </details>

          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Will recordings be provided?</summary>
            <div class="mt-2">Yes. Live sessions include downloadable notes and session recordings where applicable.</div>
          </details>
        </div>
      </div>

      <div>
        <h4 class="text-xl font-semibold">Testimonials</h4>
        <div class="mt-4 space-y-3">
          <blockquote class="bg-black/40 p-4 rounded">
            <p class="text-slate-200">"Understanding order blocks changed how I spot entries — much clearer setups and fewer losing trades." — <strong>Ashok P.</strong></p>
          </blockquote>

          <blockquote class="bg-black/40 p-4 rounded">
            <p class="text-slate-200">"The chart walkthroughs were the most valuable part — real trades, real mistakes, real fixes." — <strong>Neha S.</strong></p>
          </blockquote>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="mt-12 py-8 px-6 bg-black/40 rounded-lg flex items-center justify-between">
      <div>
        <h5 class="text-lg font-semibold">Trade with institutional clarity</h5>
        <p class="text-slate-300">Seats are limited to preserve interaction quality. Join the cohort and get hands-on feedback.</p>
      </div>
      <div>
        <button data-open-enroll="smart-money-concepts" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
          Pay Now to Enroll
        </button>
      </div>
    </div>

  </div>

  @include('partials.checkout.enroll-modal', ['course' => 'smart-money-concepts'])

</section>
@endsection
