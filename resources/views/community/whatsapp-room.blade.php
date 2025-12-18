@extends('layouts.app')

@section('title', 'WhatsApp Trading Room — Community — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">WhatsApp Trading Room</h1>
      <p class="text-slate-300 mb-6">A focused trading room where experienced traders share setups, market context, and real-time trade ideas. Join for live commentary, session open/close notes, and community Q&amp;A.</p>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">What you get</h3>
          <ul class="text-slate-300 list-disc pl-5 space-y-2">
            <li>Daily market briefing before the London and US sessions.</li>
            <li>High-probability setups and clear entry/stop/target guidance.</li>
            <li>Trade idea discussion and post-trade reviews.</li>
            <li>Priority answers to your trading questions from the moderator team.</li>
          </ul>

          <div class="mt-6">
            <h4 class="text-white font-medium mb-2">How to join</h4>
            <p class="text-slate-300 mb-4">Click the button to request an invite. We'll review and send a WhatsApp invite link or instructions.</p>
            <a href="https://wa.me/919226884183?text=I%20want%20to%20join%20the%20GOFX%20WhatsApp%20Trading%20Room" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold"
               style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
              Request Invite on WhatsApp
            </a>
          </div>
        </div>

        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Community rules</h3>
          <ul class="text-slate-300 list-disc pl-5 space-y-2">
            <li>No spam, no advertisement — keep the room focused on trading.</li>
            <li>Share setups with clear risk parameters; avoid ambiguous signals.</li>
            <li>Respect fellow members and moderators. Harassment is not tolerated.</li>
            <li>Trading is a personal responsibility — do your own risk checks.</li>
          </ul>

          <div class="mt-6">
            <h4 class="text-white font-medium mb-2">Support</h4>
            <p class="text-slate-300">Questions? Email <a href="mailto:support@gofx.in" class="underline">support@gofx.in</a> or message the WhatsApp contact.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
