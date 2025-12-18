@extends('layouts.app')

@section('title', 'Student Success Wall — Community — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-5xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Student Success Wall</h1>
      <p class="text-slate-300 mb-6">A showcase of student wins, journal highlights, and progress stories. Share your journey — it inspires others and helps build a positive community.</p>

      <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Submit your success</h3>
          <p class="text-slate-300 mb-4">Want to appear on the Success Wall? Submit a short story (title, summary, screenshot optional). We'll review and publish selected entries.</p>

          <form method="POST" action="{{ route('contact.submit') }}" class="space-y-3">
            @csrf
            {{-- reuse contact endpoint for now; later we can create a dedicated submission endpoint --}}
            <label class="block">
              <span class="text-slate-200">Your name</span>
              <input name="name" required class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
            </label>

            <label class="block">
              <span class="text-slate-200">Email</span>
              <input name="email" type="email" required class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
            </label>

            <label class="block">
              <span class="text-slate-200">Story title</span>
              <input name="subject" required class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="e.g. Doubled account in 6 months (with discipline)">
            </label>

            <label class="block">
              <span class="text-slate-200">Short summary</span>
              <textarea name="message" rows="5" required class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="Share your key actions, timeframe and results (no account details)."></textarea>
            </label>

            <div class="flex items-center gap-3">
              <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold"
                style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
                Submit for Review
              </button>

              <small class="text-slate-400">By submitting you agree we may publish an edited version of your story.</small>
            </div>
          </form>
        </div>

        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Featured</h3>
          <p class="text-slate-300 mb-4">Below are a few featured student highlights (manually curated).</p>

          <div class="space-y-4 text-slate-300">
            <div class="p-3 bg-slate-900/30 rounded">
              <div class="font-semibold text-white">Priya — Consistent profits with small position sizing</div>
              <div class="text-sm">"I focused on risk control and building a routine. Small daily improvements added up."</div>
            </div>

            <div class="p-3 bg-slate-900/30 rounded">
              <div class="font-semibold text-white">Ramesh — Discipline & journal gave clarity</div>
              <div class="text-sm">"Journaling every trade helped me identify mistakes — my winrate improved."</div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
