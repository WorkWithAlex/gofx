@extends('layouts.app')

@section('title', 'Prime Membership — Community — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-5xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Prime Membership</h1>
      <p class="text-slate-300 mb-6">Prime is a curated membership tier for traders who want deeper mentoring, advanced resources, and priority access to course updates and tools.</p>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Prime benefits</h3>
          <ul class="text-slate-300 list-disc pl-5 space-y-2">
            <li>Weekly live coaching calls and trade debriefs.</li>
            <li>Exclusive in-depth lessons & advanced strategy modules.</li>
            <li>Early access to tools, templates and course updates.</li>
            <li>Direct feedback on student trade journals (limited spots).</li>
          </ul>

          <div class="mt-6">
            <h4 class="text-white font-medium mb-2">How it works</h4>
            <p class="text-slate-300 mb-4">Prime membership is subscription-based and managed via the enrolment portal. Click the button to view membership options and apply.</p>
            <a href="{{ url('/enroll') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold"
               style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
              View Membership & Enroll
            </a>
          </div>
        </div>

        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Who should join</h3>
          <p class="text-slate-300">Prime is ideal for traders who:</p>
          <ul class="text-slate-300 list-disc pl-5 space-y-2">
            <li>Want structured mentoring and accountability.</li>
            <li>Are comfortable committing time to practice and journaling.</li>
            <li>Prefer a smaller cohort with direct access to instructors.</li>
          </ul>

          <div class="mt-6">
            <h4 class="text-white font-medium mb-2">Questions</h4>
            <p class="text-slate-300">If you have pre-sale questions, reach out at <a href="mailto:support@gofx.in" class="underline">support@gofx.in</a>.</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
