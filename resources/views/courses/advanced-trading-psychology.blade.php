@extends('layouts.app')

@section('title', 'Advanced Trading Psychology — GOFX')

@section('content')
<section class="pt-24 pb-16">
  <div class="max-w-6xl mx-auto px-6">

    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold">Advanced Trading Psychology</h1>
        <p class="mt-4 text-slate-300 max-w-xl">Master the mindset: discipline, loss management, emotional control, and the routines that separate consistent traders.</p>

        <div class="mt-6 flex gap-3">
          <!-- Pay Now button that opens modal -->
          <button data-open-enroll="advanced-trading-psychology" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Pay Now to Enroll
          </button>
          <a href="#curriculum" class="inline-block px-6 py-3 rounded-md border border-white/10 text-slate-200">
            View Curriculum
          </a>
        </div>
      </div>

      <div class="bg-black/50 rounded-xl p-6 border border-white/5">
        <h4 class="text-white font-semibold">Why this course matters</h4>
        <p class="mt-3 text-slate-300">Your edge disappears without the right mindset. Build habits that protect capital and improve decision quality.</p>
      </div>
    </div>

    <div id="curriculum" class="mt-12 space-y-6">
      <h3 class="text-2xl font-bold">Curriculum — Advanced Trading Psychology</h3>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 1 — Mindset Foundations</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Acceptance of loss & the concept of 'trading fee'</li>
          <li>Emotional awareness & cognitive biases</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 2 — Habit & Routine</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Pre-trade routines & post-trade reviews</li>
          <li>Journaling, accountability, and progress measurement</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 3 — Stress & Money Management</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Position sizing rules under stress</li>
          <li>Recovery plans and capital preservation</li>
        </ul>
      </div>

      <div class="bg-black/50 p-5 rounded-lg border border-white/5">
        <h4 class="text-lg font-semibold">Module 4 — Advanced Decision Frameworks</h4>
        <ul class="mt-3 text-slate-300 list-disc pl-5">
          <li>Decision trees, probabilistic thinking, and edge measurement</li>
          <li>Live behavioral drills and peer review</li>
        </ul>
      </div>
    </div>

    <!-- FAQs & Testimonials -->
    <div class="mt-12 grid md:grid-cols-2 gap-8">
      <div>
        <h4 class="text-xl font-semibold">FAQs</h4>
        <div class="mt-4 space-y-3 text-slate-300">
          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Is this course theoretical?</summary>
            <div class="mt-2">It mixes theory with practical habit-building exercises and live drills to change behaviour over time.</div>
          </details>

          <details class="bg-black/40 p-3 rounded"><summary class="font-semibold">Who teaches this?</summary>
            <div class="mt-2">Trainers and mentors with live trading experience (Tehseen & team).</div>
          </details>
        </div>
      </div>

      <div>
        <h4 class="text-xl font-semibold">Testimonials</h4>
        <div class="mt-4 space-y-3">
          <blockquote class="bg-black/40 p-4 rounded">
            <p class="text-slate-200">"Discipline changes everything — the drills helped reduce revenge trading." — <strong>Ramesh T.</strong></p>
          </blockquote>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="mt-12 py-8 px-6 bg-black/40 rounded-lg flex items-center justify-between">
      <div>
        <h5 class="text-lg font-semibold">Fix your psychology — protect your edge</h5>
        <p class="text-slate-300">Seats are limited to preserve interaction quality.</p>
      </div>
      <div>
        <button data-open-enroll="advanced-trading-psychology" class="inline-block px-6 py-3 font-semibold rounded-md" style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
          Pay Now to Enroll
        </button>
      </div>
    </div>

  </div>
  
  @include('partials.checkout.enroll-modal', ['course' => 'advanced-trading-psychology'])

</section>
@endsection
