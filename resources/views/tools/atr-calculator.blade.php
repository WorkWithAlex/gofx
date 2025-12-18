@extends('layouts.app')

@section('title', 'ATR Calculator — Tools — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-5xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">ATR Calculator (Wilder)</h1>
      <p class="text-slate-300 mb-6">Paste recent candles (high, low, close per line). This tool computes True Range (TR) and the ATR series using Wilder's smoothing (first ATR = simple average of first N TRs).</p>

      <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-slate-800/30 rounded-lg p-6">
          <form id="atrForm" onsubmit="return false;">
            @csrf

            <label class="block mb-3">
              <span class="text-slate-200">Candles (one per line: high,low,close)</span>
              <textarea id="candles" name="candles" rows="10" placeholder="1.1250,1.1200,1.1225
1.1275,1.1220,1.1250
1.1300,1.1240,1.1280" class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"></textarea>
              <small class="text-slate-400">Provide at least as many lines as the ATR period (default 14). Values can be comma or space separated.</small>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">ATR period</span>
              <input id="period" name="period" type="number" step="1" min="1" value="14" class="mt-1 block w-36 rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
            </label>

            <div class="flex gap-3 mt-4">
              <button id="calcBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-bold bg-gradient-to-r from-[#f7931a] to-[#ffd166] text-black">Calculate</button>
              <button id="resetBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/5 text-slate-200">Reset</button>
              <button id="exampleBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/5 text-slate-200">Insert Example</button>
            </div>
          </form>

          <div id="errorBox" class="mt-4 hidden rounded-md bg-red-900/70 p-3 text-white"></div>
        </div>

        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Result</h3>
          <div id="results" class="text-slate-200">
            <p class="text-slate-400">Paste candles and click Calculate. The last ATR value will be shown along with a small table of recent TR & ATR values.</p>
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
  const exampleBtn = document.getElementById('exampleBtn');
  const resultsBox = document.getElementById('results');
  const errorBox = document.getElementById('errorBox');

  function showError(msg){
    errorBox.textContent = msg;
    errorBox.classList.remove('hidden');
  }
  function hideError(){
    errorBox.textContent = '';
    errorBox.classList.add('hidden');
  }

  function parseCandles(text){
    const lines = text.split(/\r\n|\n|\r/).map(l => l.trim()).filter(Boolean);
    const candles = [];
    for (let i=0;i<lines.length;i++){
      const parts = lines[i].split(/[, \t]+/).filter(Boolean);
      if (parts.length < 3) {
        throw new Error('Invalid line '+(i+1)+'. Expected high,low,close.');
      }
      let h = parseFloat(parts[0]);
      let l = parseFloat(parts[1]);
      let c = parseFloat(parts[2]);
      if (isNaN(h) || isNaN(l) || isNaN(c)) throw new Error('Invalid numeric value on line '+(i+1));
      // swap if high < low
      if (h < l) { const tmp = h; h = l; l = tmp; }
      candles.push({high: h, low: l, close: c});
    }
    return candles;
  }

  function computeAtrFromCandles(candles, period){
    const count = candles.length;
    if (count < period) throw new Error('Need at least '+period+' candles (you provided '+count+').');

    // TRs
    const trs = [];
    for (let i=0;i<count;i++){
      const high = candles[i].high;
      const low = candles[i].low;
      const prevClose = (i>0) ? candles[i-1].close : null;
      let candidates = [high - low];
      if (prevClose !== null) {
        candidates.push(Math.abs(high - prevClose));
        candidates.push(Math.abs(low - prevClose));
      }
      trs.push(Math.max(...candidates));
    }

    // ATR using Wilder
    const atrs = new Array(count).fill(null);
    // first ATR at index period-1 is simple average of first N TRs
    const firstWindow = trs.slice(0, period);
    const firstAtr = firstWindow.reduce((s,v)=>s+v,0)/period;
    atrs[period-1] = firstAtr;

    for (let i=period;i<count;i++){
      const prev = atrs[i-1] !== null ? atrs[i-1] : firstAtr;
      const atr = ((prev * (period - 1)) + trs[i]) / period;
      atrs[i] = atr;
    }

    // return arrays
    return {trs, atrs};
  }

  function renderResults(json){
    // json: { period, count_candles, tr_series, atr_series, atr_last }
    const atrLast = json.atr_last;
    let html = `<div>
      <div class="mb-3"><strong>ATR (last):</strong> ${atrLast !== null ? atrLast.toFixed(8) : '-'}</div>
      <div class="text-slate-400 text-sm mb-3">Period: ${json.period} • Candles: ${json.count_candles}</div>
    </div>`;

    // show last up to 8 rows
    const total = json.count_candles;
    const maxRows = 8;
    const start = Math.max(0, total - maxRows);
    html += `<div class="overflow-auto"><table class="w-full text-sm"><thead><tr class="text-slate-300"><th class="text-left pr-3">#</th><th class="text-left pr-3">TR</th><th class="text-left">ATR</th></tr></thead><tbody>`;

    for (let i = start; i < total; i++){
      const tr = json.tr_series[i] !== undefined ? json.tr_series[i] : null;
      const atr = json.atr_series[i] !== undefined ? json.atr_series[i] : null;
      html += `<tr class="border-t border-white/5"><td class="py-2 text-slate-200">${i+1}</td><td class="py-2 text-slate-200">${tr !== null ? tr.toFixed(8) : '-'}</td><td class="py-2 text-slate-200">${atr !== null ? atr.toFixed(8) : '-'}</td></tr>`;
    }

    html += `</tbody></table></div>`;
    resultsBox.innerHTML = html;
  }

  calcBtn.addEventListener('click', async (e)=>{
    e.preventDefault();
    hideError();

    const candlesText = document.getElementById('candles').value || '';
    const period = parseInt(document.getElementById('period').value || '14', 10);

    try {
      const candles = parseCandles(candlesText);
      // local compute for instant UX
      const local = computeAtrFromCandles(candles, period);
      // Build a small JSON to display locally (we want to show immediate result)
      const localJson = {
        period: period,
        count_candles: candles.length,
        tr_series: local.trs,
        atr_series: local.atrs,
        atr_last: (function(){
          for (let i = local.atrs.length-1; i>=0; i--){
            if (local.atrs[i] !== null) return local.atrs[i];
          }
          return null;
        })()
      };
      renderResults(localJson);

      // server verify
      const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
      const res = await fetch("{{ route('tools.atr.calc') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? {'X-CSRF-TOKEN': token} : {})
        },
        body: JSON.stringify({candles: candlesText, period: period})
      });

      if (!res.ok) {
        const err = await res.json().catch(()=>({message:'Server error'}));
        if (err && err.error) showError(err.error);
        else showError(err.message || 'Server calculation failed');
        return;
      }

      const json = await res.json();
      renderResults(json);
    } catch (err) {
      showError(err.message || 'Invalid input');
    }
  });

  resetBtn.addEventListener('click', (e)=>{
    e.preventDefault();
    document.getElementById('candles').value = '';
    document.getElementById('period').value = '14';
    hideError();
    resultsBox.innerHTML = `<p class="text-slate-400">Paste candles and click Calculate. The last ATR value will be shown along with recent TR & ATR.</p>`;
  });

  exampleBtn.addEventListener('click', (e)=>{
    e.preventDefault();
    // simple 20-line example (synthetic)
    const sample = [
      "1.1000,1.0950,1.0980",
      "1.1020,1.0970,1.1000",
      "1.1050,1.1010,1.1030",
      "1.1070,1.1020,1.1060",
      "1.1100,1.1050,1.1080",
      "1.1120,1.1090,1.1105",
      "1.1150,1.1110,1.1130",
      "1.1180,1.1140,1.1170",
      "1.1200,1.1160,1.1185",
      "1.1220,1.1180,1.1210",
      "1.1250,1.1200,1.1230",
      "1.1270,1.1230,1.1260",
      "1.1290,1.1250,1.1280",
      "1.1310,1.1270,1.1300",
      "1.1330,1.1300,1.1320",
      "1.1350,1.1310,1.1340",
      "1.1370,1.1330,1.1360",
      "1.1390,1.1350,1.1380",
      "1.1410,1.1370,1.1400",
      "1.1430,1.1390,1.1420"
    ].join("\\n");
    document.getElementById('candles').value = sample;
    hideError();
  });

  // init
  document.getElementById('period').value = '14';
})();
</script>
@endpush
