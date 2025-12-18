@extends('layouts.app')

@section('title', 'Risk/Reward Calculator — Tools — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Risk / Reward Calculator</h1>
      <p class="text-slate-300 mb-6">Calculate stop &amp; target distances (in pips), the R:R ratio and required winrate. Optionally enter lots to see monetary risk/reward.</p>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-slate-800/30 rounded-lg p-6">
          <form id="rrForm" onsubmit="return false;">
            @csrf

            <label class="block mb-3">
              <span class="text-slate-200">Entry price</span>
              <input id="entry_price" name="entry_price" type="number" step="0.00001" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="e.g. 1.12345" required>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Stop price</span>
              <input id="stop_price" name="stop_price" type="number" step="0.00001" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="e.g. 1.12000" required>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Take profit price</span>
              <input id="take_profit_price" name="take_profit_price" type="number" step="0.00001" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="e.g. 1.13000" required>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Pip multiplier</span>
              <input id="pip_multiplier" name="pip_multiplier" type="number" step="1" min="1" value="10000" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
              <small class="text-slate-400">Use 10000 for 4-decimal pairs (EURUSD), 100 for JPY pairs.</small>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Pip value per standard lot (optional)</span>
              <input id="pip_value" name="pip_value" type="number" step="0.0001" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="Leave empty for default ≈ 10">
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Lots (standard) — optional</span>
              <input id="lots" name="lots" type="number" step="0.0001" min="0" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="e.g. 0.2">
              <small class="text-slate-400">If provided, monetary risk & reward will be shown.</small>
            </label>

            <div class="flex gap-3 mt-4">
              <button id="calcBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-bold bg-gradient-to-r from-[#f7931a] to-[#ffd166] text-black">Calculate</button>
              <button id="resetBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/5 text-slate-200">Reset</button>
            </div>
          </form>

          <div id="errorBox" class="mt-4 hidden rounded-md bg-red-900/70 p-3 text-white"></div>
        </div>

        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Result</h3>
          <div id="results" class="text-slate-200">
            <p class="text-slate-400">Fill the form and click Calculate. Results will appear here.</p>
          </div>

          <div class="mt-6">
            <h4 class="text-sm text-slate-300 mb-2">Interpretation</h4>
            <ul class="text-slate-400 list-disc pl-5 space-y-2 text-sm">
              <li>Required winrate = 1 / (1 + R:R). If R:R is 2, you need ~33.3% winrate to breakeven.</li>
              <li>Use pip multiplier to adapt to instrument (10000 for EURUSD, 100 for USDJPY).</li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
(function(){
  const calcBtn = document.getElementById('calcBtn');
  const resetBtn = document.getElementById('resetBtn');
  const resultsBox = document.getElementById('results');
  const errorBox = document.getElementById('errorBox');

  function showError(msg){
    errorBox.textContent = msg;
    errorBox.classList.remove('hidden');
  }
  function hideError(){
    errorBox.classList.add('hidden');
    errorBox.textContent = '';
  }

  function formatNumber(n, decimals=4){
    if (n === null || n === undefined || isNaN(n)) return '-';
    return Number(n).toLocaleString(undefined, { maximumFractionDigits: decimals });
  }

  calcBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    hideError();

    const entry = parseFloat(document.getElementById('entry_price').value || '0');
    const stop = parseFloat(document.getElementById('stop_price').value || '0');
    const tp = parseFloat(document.getElementById('take_profit_price').value || '0');
    const pipMultiplier = parseFloat(document.getElementById('pip_multiplier').value || '10000');
    const pipValueRaw = document.getElementById('pip_value').value;
    const pipValue = pipValueRaw ? parseFloat(pipValueRaw) : null;
    const lotsRaw = document.getElementById('lots').value;
    const lots = lotsRaw ? parseFloat(lotsRaw) : null;

    if (!(entry > 0) || !(stop > 0) || !(tp > 0)) { showError('Please enter valid positive prices'); return; }
    if (!(pipMultiplier > 0)) { showError('Please enter a valid pip multiplier'); return; }

    // Local compute for instant UX
    const stopPips = Math.abs(entry - stop) * pipMultiplier;
    const rewardPips = Math.abs(tp - entry) * pipMultiplier;
    if (stopPips <= 0) { showError('Computed stop pips is zero. Check entry/stop prices'); return; }

    const rr = rewardPips / stopPips;
    const requiredWinrate = rr > 0 ? (1 / (1 + rr)) * 100 : 100;

    // monetary if lots provided
    const pv = (pipValue && pipValue > 0) ? pipValue : 10.0;
    const riskAmt = (lots && lots > 0) ? (lots * stopPips * pv) : null;
    const rewardAmt = (lots && lots > 0) ? (lots * rewardPips * pv) : null;

    // render local result
    resultsBox.innerHTML = `
      <div class="grid gap-2">
        <div><strong>Stop (pips):</strong> ${formatNumber(stopPips,4)}</div>
        <div><strong>Reward (pips):</strong> ${formatNumber(rewardPips,4)}</div>
        <div><strong>R : R ratio:</strong> ${formatNumber(rr,4)} : 1</div>
        <div><strong>Required winrate (break-even):</strong> ${formatNumber(requiredWinrate,2)}%</div>
        ${riskAmt !== null ? `<div><strong>Monetary risk:</strong> ${formatNumber(riskAmt,2)}</div>` : ''}
        ${rewardAmt !== null ? `<div><strong>Monetary reward:</strong> ${formatNumber(rewardAmt,2)}</div>` : ''}
        <div class="text-slate-400 text-sm mt-2">Using pip value per standard lot: ${formatNumber(pv,4)}</div>
      </div>
    `;

    // POST server verify
    try {
      const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
      const res = await fetch("{{ route('tools.risk_reward.calc') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? {'X-CSRF-TOKEN': token} : {})
        },
        body: JSON.stringify({
          entry_price: entry,
          stop_price: stop,
          take_profit_price: tp,
          pip_multiplier: pipMultiplier,
          pip_value: pipValue,
          lots: lots
        })
      });

      if (!res.ok) {
        const err = await res.json().catch(()=>({message:'Server error'}));
        if (err && err.error) showError(err.error);
        else showError(err.message || 'Server calculation failed');
        return;
      }

      const json = await res.json();
      // render server-verified result
      resultsBox.innerHTML = `
        <div class="grid gap-2">
          <div><strong>Stop (pips):</strong> ${formatNumber(json.stop_pips,4)}</div>
          <div><strong>Reward (pips):</strong> ${formatNumber(json.reward_pips,4)}</div>
          <div><strong>R : R ratio:</strong> ${formatNumber(json.rr_ratio,4)} : 1</div>
          <div><strong>Required winrate (break-even):</strong> ${formatNumber(json.required_winrate_percent,2)}%</div>
          ${json.risk_amount !== null ? `<div><strong>Monetary risk:</strong> ${formatNumber(json.risk_amount,2)}</div>` : ''}
          ${json.reward_amount !== null ? `<div><strong>Monetary reward:</strong> ${formatNumber(json.reward_amount,2)}</div>` : ''}
          <div class="text-slate-400 text-sm mt-2">Using pip value per standard lot: ${formatNumber(json.pip_value,4)}</div>
        </div>
      `;
    } catch (err) {
      console.error(err);
      const note = document.createElement('div');
      note.className = 'text-yellow-400 text-sm mt-3';
      note.textContent = 'Server verification failed (offline). Results above are locally computed.';
      resultsBox.appendChild(note);
    }
  });

  resetBtn.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('entry_price').value = '';
    document.getElementById('stop_price').value = '';
    document.getElementById('take_profit_price').value = '';
    document.getElementById('pip_multiplier').value = '10000';
    document.getElementById('pip_value').value = '';
    document.getElementById('lots').value = '';
    hideError();
    resultsBox.innerHTML = `<p class="text-slate-400">Fill the form and click Calculate. Results will appear here.</p>`;
  });
})();
</script>
@endpush
