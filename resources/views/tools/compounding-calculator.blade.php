@extends('layouts.app')

@section('title', 'Compounding Calculator — Tools — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Compounding Calculator</h1>
      <p class="text-slate-300 mb-6">Simulate growth of capital with periodic contributions and compounding. Choose contribution timing (beginning or end of period) and compounding frequency (monthly, yearly, etc.).</p>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-slate-800/30 rounded-lg p-6">
          <form id="compForm" onsubmit="return false;">
            @csrf

            <label class="block mb-3">
              <span class="text-slate-200">Initial balance</span>
              <input id="initial_balance" name="initial_balance" type="number" step="0.01" min="0"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" value="10000" required>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Periodic contribution (per period)</span>
              <input id="periodic_contribution" name="periodic_contribution" type="number" step="0.01" min="0"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" placeholder="e.g. 100">
              <small class="text-slate-400">Leave blank or 0 for no periodic contributions.</small>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Annual return (%)</span>
              <input id="annual_return_percent" name="annual_return_percent" type="number" step="0.01"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" value="12" required>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Years</span>
              <input id="years" name="years" type="number" step="0.1" min="0.01"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white" value="10" required>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Compounding per year</span>
              <select id="compounding_per_year" name="compounding_per_year" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
                <option value="1">Yearly (1)</option>
                <option value="2">Semi-annual (2)</option>
                <option value="4">Quarterly (4)</option>
                <option value="12" selected>Monthly (12)</option>
                <option value="365">Daily (365)</option>
              </select>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Contribution timing</span>
              <select id="contribution_timing" name="contribution_timing" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
                <option value="end" selected>End of period (ordinary annuity)</option>
                <option value="begin">Beginning of period (annuity due)</option>
              </select>
            </label>

            <div class="flex gap-3 mt-4">
              <button id="calcBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-bold bg-gradient-to-r from-[#f7931a] to-[#ffd166] text-black">Calculate</button>
              <button id="resetBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/5 text-slate-200">Reset</button>
            </div>
          </form>

          <div id="errorBox" class="mt-4 hidden rounded-md bg-red-900/70 p-3 text-white"></div>
        </div>

        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Summary</h3>
          <div id="summary" class="text-slate-200">
            <p class="text-slate-400">Fill inputs and click Calculate to see final balance, total contributions and gain. A period-by-period table will appear below.</p>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-slate-900/40 p-4 rounded-lg">
        <h4 class="text-white font-semibold mb-2">Period breakdown</h4>
        <div id="seriesContainer" class="text-slate-200 overflow-auto max-h-72"></div>
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
  const resultsSummary = document.getElementById('summary');
  const seriesContainer = document.getElementById('seriesContainer');
  const errorBox = document.getElementById('errorBox');

  function showError(msg){
    errorBox.textContent = msg;
    errorBox.classList.remove('hidden');
  }
  function hideError(){
    errorBox.textContent = '';
    errorBox.classList.add('hidden');
  }

  function formatMoney(n){
    if (n === null || n === undefined || isNaN(n)) return '-';
    return Number(n).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function computeLocally(P0, PMT, annualPct, years, n, timing) {
    const r = annualPct / 100.0;
    const periods = Math.round(n * years);
    const periodRate = r / n;

    let balance = P0;
    let cumulative = 0;
    const series = [];

    for (let i=1;i<=periods;i++){
      if (timing === 'begin' && PMT > 0) {
        balance += PMT;
        cumulative += PMT;
      }
      balance = balance * (1 + periodRate);
      if (timing === 'end' && PMT > 0) {
        balance += PMT;
        cumulative += PMT;
      }
      const gain = balance - P0 - cumulative;
      series.push({
        period_index: i,
        balance: balance,
        contribution_this_period: PMT,
        cumulative_contributed: cumulative,
        gain: gain,
      });
    }

    const final = series.length ? series[series.length-1].balance : P0;
    return {
      summary: {
        initial_balance: P0,
        final_balance: final,
        total_contributed: cumulative,
        total_gain: final - P0 - cumulative,
        periods: periods,
        period_rate: periodRate,
      },
      series: series
    };
  }

  function renderResults(json){
    const s = json.summary;
    resultsSummary.innerHTML = `
      <div>
        <div class="mb-1"><strong>Final balance:</strong> ${formatMoney(s.final_balance)}</div>
        <div class="mb-1"><strong>Total contributed:</strong> ${formatMoney(s.total_contributed)}</div>
        <div class="mb-1"><strong>Total gain:</strong> ${formatMoney(s.total_gain)}</div>
        <div class="text-slate-400 text-sm">Periods: ${s.periods} • Period rate: ${(s.period_rate*100).toFixed(6)}%</div>
      </div>
    `;

    // render series (show last 50 rows at most)
    const rows = json.series;
    if (!rows || rows.length === 0) {
      seriesContainer.innerHTML = `<p class="text-slate-400">No series data.</p>`;
      return;
    }

    const max = 100; // cap output to avoid huge DOM
    const start = Math.max(0, rows.length - max);
    let html = `<table class="w-full text-sm"><thead><tr class="text-slate-300"><th>#</th><th>Balance</th><th>Contribution</th><th>Cumulative</th><th>Gain</th></tr></thead><tbody>`;
    for (let i=start;i<rows.length;i++){
      const row = rows[i];
      html += `<tr class="border-t border-white/5"><td class="py-1">${row.period_index}</td><td class="py-1">${formatMoney(row.balance)}</td><td class="py-1">${formatMoney(row.contribution_this_period)}</td><td class="py-1">${formatMoney(row.cumulative_contributed)}</td><td class="py-1">${formatMoney(row.gain)}</td></tr>`;
    }
    html += `</tbody></table>`;
    if (rows.length > max) {
      html = `<div class="text-slate-400 text-sm mb-2">Showing last ${max} periods (total ${rows.length}).</div>` + html;
    }
    seriesContainer.innerHTML = html;
  }

  calcBtn.addEventListener('click', async (e)=>{
    e.preventDefault();
    hideError();

    const P0 = parseFloat(document.getElementById('initial_balance').value || 0);
    const PMT = parseFloat(document.getElementById('periodic_contribution').value || 0);
    const annualPct = parseFloat(document.getElementById('annual_return_percent').value || 0);
    const years = parseFloat(document.getElementById('years').value || 0);
    const n = parseInt(document.getElementById('compounding_per_year').value || 12, 10);
    const timing = document.getElementById('contribution_timing').value || 'end';

    if (!(P0 >= 0)) { showError('Initial balance must be >= 0'); return; }
    if (!(annualPct > -100)) { showError('Annual return seems invalid'); return; }
    if (!(years > 0)) { showError('Years must be > 0'); return; }
    if (!(n >= 1)) { showError('Compounding per year must be >= 1'); return; }

    // local compute
    const local = computeLocally(P0, PMT, annualPct, years, n, timing);
    renderResults(local);

    // server verify
    try {
      const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
      const res = await fetch("{{ route('tools.compounding.calc') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? {'X-CSRF-TOKEN': token} : {})
        },
        body: JSON.stringify({
          initial_balance: P0,
          periodic_contribution: PMT,
          annual_return_percent: annualPct,
          years: years,
          compounding_per_year: n,
          contribution_timing: timing
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
      renderResults(json);
    } catch (err) {
      console.error(err);
      const note = document.createElement('div');
      note.className = 'text-yellow-400 text-sm mt-3';
      note.textContent = 'Server verification failed (offline). Results above are locally computed.';
      seriesContainer.appendChild(note);
    }

  });

  resetBtn.addEventListener('click', (e)=>{
    e.preventDefault();
    document.getElementById('initial_balance').value = '10000';
    document.getElementById('periodic_contribution').value = '';
    document.getElementById('annual_return_percent').value = '12';
    document.getElementById('years').value = '10';
    document.getElementById('compounding_per_year').value = '12';
    document.getElementById('contribution_timing').value = 'end';
    hideError();
    resultsSummary.innerHTML = `<p class="text-slate-400">Fill inputs and click Calculate to see final balance, total contributions and gain.</p>`;
    seriesContainer.innerHTML = '';
  });
})();
</script>
@endpush
