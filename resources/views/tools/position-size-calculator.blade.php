@extends('layouts.app')

@section('title', 'Position Size Calculator — Tools — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Position Size Calculator</h1>
      <p class="text-slate-300 mb-6">Calculate how many lots to trade based on account size, risk percentage and stop loss (pips).</p>

      <div class="grid md:grid-cols-2 gap-6">
        <!-- Form -->
        <div class="bg-slate-800/30 rounded-lg p-6">
          <form id="posCalcForm" onsubmit="return false;">
            @csrf

            <label class="block mb-4">
              <span class="text-slate-200">Account size (in your account currency)</span>
              <input id="account_size" name="account_size" type="number" step="0.01" min="0"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"
                     placeholder="e.g. 10000" value="10000" required>
            </label>

            <label class="block mb-4">
              <span class="text-slate-200">Risk per trade (%)</span>
              <input id="risk_percent" name="risk_percent" type="number" step="0.01" min="0"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"
                     placeholder="e.g. 1" value="1" required>
            </label>

            <label class="block mb-4">
              <span class="text-slate-200">Stop loss (pips)</span>
              <input id="stop_pips" name="stop_pips" type="number" step="0.1" min="0.1"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"
                     placeholder="e.g. 50" value="50" required>
            </label>

            <label class="block mb-4">
              <span class="text-slate-200">Pip value per standard lot (optional)</span>
              <input id="pip_value" name="pip_value" type="number" step="0.0001" min="0.0001"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"
                     placeholder="Leave empty to use default (≈ 10 for USD-quoted pairs)">
            </label>

            <div class="flex gap-3 items-center mt-4">
              <button id="calculateBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-bold bg-gradient-to-r from-[#f7931a] to-[#ffd166] text-black">
                Calculate
              </button>

              <button id="resetBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/5 text-slate-200">
                Reset
              </button>
            </div>
          </form>

          <div id="errorBox" class="mt-4 hidden rounded-md bg-red-900/70 p-3 text-white"></div>
        </div>

        <!-- Results -->
        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Result</h3>

          <div id="results" class="space-y-3 text-slate-200">
            <p class="text-slate-400">Fill the form and click <strong>Calculate</strong> to see position sizing suggestions.</p>
          </div>

          <div class="mt-6">
            <h4 class="text-sm text-slate-300 mb-2">Notes</h4>
            <ul class="text-slate-400 list-disc pl-5 space-y-2 text-sm">
              <li>If you leave <strong>pip value</strong> empty, the calculator uses a default <strong>10</strong> (approx. USD-quoted pairs per standard lot).</li>
              <li>1 standard lot = 10 mini lots = 100 micro lots.</li>
              <li>This is a planning tool only — always verify with your broker and check margin/leverage rules.</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Examples -->
      <div class="mt-8 bg-slate-900/40 p-4 rounded-lg">
        <h4 class="text-white font-semibold mb-2">Example</h4>
        <p class="text-slate-300 text-sm">Account 10,000 • Risk 1% • Stop 50 pips • pip value 10 → risk amount = 100 → lots = 100 / (50*10) = 0.2 standard lots (20 mini / 2000 micro)</p>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
(function(){
  const form = document.getElementById('posCalcForm');
  const calcBtn = document.getElementById('calculateBtn');
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

  function formatNumber(n, decimals = 6){
    if (n === null || n === undefined || isNaN(n)) return '-';
    return Number(n).toLocaleString(undefined, { maximumFractionDigits: decimals });
  }

  function computeLocally(account, riskPct, stopPips, pipValue){
    const riskAmount = account * (riskPct / 100.0);
    const pv = (pipValue && pipValue > 0) ? pipValue : 10.0;
    const lotsStandard = riskAmount / (stopPips * pv);
    const lotsMini = lotsStandard * 10;
    const lotsMicro = lotsStandard * 100;
    const notional = lotsStandard * 100000 * (pv / 10);
    return {
      account_size: account,
      risk_percent: riskPct,
      risk_amount: riskAmount,
      stop_pips: stopPips,
      pip_value: pv,
      lots_standard: lotsStandard,
      lots_mini: lotsMini,
      lots_micro: lotsMicro,
      notional: notional
    };
  }

  function renderResult(data){
    resultsBox.innerHTML = `
      <div class="grid grid-cols-1 gap-2">
        <div><strong>Risk amount:</strong> ${formatNumber(data.risk_amount,2)}</div>
        <div><strong>Standard lots:</strong> ${formatNumber(data.lots_standard,6)} std</div>
        <div><strong>Mini lots:</strong> ${formatNumber(data.lots_mini,4)} mini</div>
        <div><strong>Micro lots:</strong> ${formatNumber(data.lots_micro,2)} micro</div>
        <div><strong>Approx. notional (est):</strong> ${formatNumber(data.notional,2)}</div>
        <div class="text-slate-400 text-sm mt-2">Used pip value per standard lot: ${formatNumber(data.pip_value,4)}</div>
        <div class="text-slate-400 text-sm">Note: validation and server verification run after local calculation.</div>
      </div>
    `;
  }

  calcBtn.addEventListener('click', async function(e){
    e.preventDefault();
    hideError();

    const account = parseFloat(document.getElementById('account_size').value || 0);
    const riskPct = parseFloat(document.getElementById('risk_percent').value || 0);
    const stopPips = parseFloat(document.getElementById('stop_pips').value || 0);
    const pipValueRaw = document.getElementById('pip_value').value;
    const pipValue = pipValueRaw ? parseFloat(pipValueRaw) : null;

    // local validation
    if (!(account > 0)) { showError('Please enter a valid account size (> 0).'); return; }
    if (!(riskPct > 0)) { showError('Please enter a valid risk percent (> 0).'); return; }
    if (!(stopPips > 0)) { showError('Please enter a valid stop in pips (> 0).'); return; }
    if (pipValueRaw && !(pipValue > 0)) { showError('If provided, pip value must be > 0.'); return; }

    // local compute & render immediately
    const local = computeLocally(account, riskPct, stopPips, pipValue);
    renderResult(local);

    // Also POST to server for verification & to future-proof storing
    try {
      const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
      const res = await fetch("{{ route('tools.position_size.calc') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? {'X-CSRF-TOKEN': token} : {})
        },
        body: JSON.stringify({
          account_size: account,
          risk_percent: riskPct,
          stop_pips: stopPips,
          pip_value: pipValue
        })
      });

      if (!res.ok) {
        const err = await res.json().catch(()=>({message:'Server error'}));
        if (err && err.error) showError(err.error);
        else showError(err.message || 'Server calculation failed');
        return;
      }

      const json = await res.json();
      // prefer server result as verified (but keep local until server returns)
      renderResult(json);
    } catch (err){
      console.error(err);
      // network error: keep local result
      // but notify user non-blocking
      const note = document.createElement('div');
      note.className = 'text-yellow-400 text-sm mt-3';
      note.textContent = 'Server verification failed (offline). Results above are locally computed.';
      resultsBox.appendChild(note);
    }
  });

  resetBtn.addEventListener('click', function(e){
    e.preventDefault();
    document.getElementById('account_size').value = '10000';
    document.getElementById('risk_percent').value = '1';
    document.getElementById('stop_pips').value = '50';
    document.getElementById('pip_value').value = '';
    hideError();
    resultsBox.innerHTML = `<p class="text-slate-400">Fill the form and click <strong>Calculate</strong> to see position sizing suggestions.</p>`;
  });
})();
</script>
@endpush
