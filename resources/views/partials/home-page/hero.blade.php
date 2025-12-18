<section class="relative flex items-center justify-center py-20">

  <!-- {{-- Background overlay (important for neon contrast) --}} -->
  <!-- <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#05070e]/40 via-[#060710]/90 to-[#03060b]"></div> -->

  <div class="wrap w-full max-w-5xl px-6 flex items-center justify-center">
    <div class="glass-neon-blue pulse-animated overflow-visible text-center rounded-2xl p-8 py-24 grid md:grid-cols-1 gap-6">

      {{-- CONTENT --}}
      <div class="space-y-6">

        {{-- Headline --}}
        <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">
          Trade <span style="color:var(--accent1)">gold</span> &
          <span style="color:var(--accent2)">bitcoin</span>
          with an Institutional Edge
        </h1>

        {{-- Subheadline --}}
        <p class="text-slate-300 max-w-xl mx-auto">
          Practical, no-fluff trading education led by Tehseen Shaikh — live market sessions,
          repeatable execution playbooks, and a community that levels up together.
          Join early to access alpha lessons, live calls and priority onboarding.
        </p>

        {{-- Badges --}}
        <div class="flex flex-wrap gap-3 mt-3 justify-center">
          <span class="px-3 py-1 rounded-full bg-white/5 text-sm">Live Market Calls</span>
          <span class="px-3 py-1 rounded-full bg-white/5 text-sm">Execution Playbooks</span>
          <span class="px-3 py-1 rounded-full bg-white/5 text-sm">Risk Management</span>
          <span class="px-3 py-1 rounded-full bg-white/5 text-sm">Community & Support</span>
        </div>

        {{-- CTAs --}}
        <div class="mt-6 flex flex-wrap justify-center gap-4">
          <a href="{{ url('/enroll') }}" class="inline-block px-6 py-3 rounded-md font-semibold"
             style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Join Waitlist
          </a>

          <a href="{{ url('/courses') }}" 
             class="inline-block px-5 py-3 rounded-md border border-white/10 text-slate-200">
            View Curriculum
          </a>
        </div>

        {{-- Disclaimer --}}
        <div class="text-sm text-slate-400 mt-4">
          Trusted approach • Real-world execution •
          <span class="text-xs">Not financial advice — educational only</span>
        </div>

      </div>

    </div>
  </div>
</section>
