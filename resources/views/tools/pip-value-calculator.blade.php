@extends('layouts.app')

@section('title', 'Pip Value Calculator — Tools — GOFX')

@section('content')
<section class="py-20">
  <div class="max-w-4xl mx-auto px-6">
    <div class="bg-slate-900/40 rounded-2xl p-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Pip Value Calculator</h1>
      <p class="text-slate-300 mb-6">Auto-calculates pip value per standard lot (100,000 units). Supports USD-quoted pairs and JPY pairs. You can optionally convert the pip value into another currency by supplying a conversion rate.</p>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-slate-800/30 rounded-lg p-6">
          <form id="pipForm" onsubmit="return false;">
            @csrf

            <label class="block mb-3">
              <span class="text-slate-200">Currency pair</span>
              <input id="pair" name="pair" type="text" value="EURUSD"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"
                     placeholder="e.g. EURUSD, USDJPY, EURJPY" required>
              <small class="text-slate-400">Enter pair without slash, e.g. EURUSD or USDJPY. Common presets available via JS.</small>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Convert pip value to (optional)</span>
              <input id="convert_to" name="convert_to" type="text" value="USD"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"
                     placeholder="e.g. USD (optional)">
              <small class="text-slate-400">Target currency for conversion (e.g. USD). Leave empty to just see pip value in quote currency.</small>
            </label>

            <label class="block mb-3">
              <span class="text-slate-200">Conversion rate (optional)</span>
              <input id="conversion_rate" name="conversion_rate" type="number" step="0.000001"
                     class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white"
                     placeholder="If converting JPY->USD use USDJPY rate, e.g. 150.00">
              <small class="text-slate-400">If converting pip value to another currency, provide the correct conversion rate (quote→target). For JPY->USD provide USDJPY.</small>
            </label>

            <div class="flex gap-3 mt-4">
              <button id="calcBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-bold bg-gradient-to-r from-[#f7931a] to-[#ffd166] text-black">Calculate</button>
              <button id="presetBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/5 text-slate-200">Presets</button>
              <button id="resetBtn" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/5 text-slate-200">Reset</button>
            </div>
          </form>

          <div id="errorBox" class="mt-4 hidden rounded-md bg-red-900/70 p-3 text-white"></div>
        </div>

        <div class="bg-slate-800/30 rounded-lg p-6">
          <h3 class="text-xl font-semibold text-white mb-3">Result</h3>
          <div id="results" class="text-slate-200">
            <p class="text-slate-400">Fill the pair and click Calculate. The pip value for 1 standard lot (100,000 base units) will be shown.</p>
          </div>

          <div class="mt-6">
            <h4 class="text-sm text-slate-300 mb-2">Quick reference</h4>
            <ul class="text-slate-400 list-disc pl-5 space-y-2 text-sm">
              <li>EURUSD, GBPUSD, AUDUSD — pip ≈ 0.0001 → pip value ≈ 10 USD per standard lot.</li>
              <li>USDJPY, EURJPY — pip ≈ 0.01 → pip value ≈ 1000 JPY per standard lot. Convert to your account currency using USDJPY rate.</li>
              <li>You can supply conversion rate to see pip value in your account currency.</li>
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
  const presetBtn = document.getElementById('presetBtn');
  const resetBtn = document.getElementById('resetBtn');
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

  function formatNumber(n, d=6){
    if (n === null || n === undefined || isNaN(n)) return '-';
    return Number(n).toLocaleString(undefined, { maximumFractionDigits: d });
  }

  // Presets popup (basic)
  presetBtn.addEventListener('click', (e)=>{
    e.preventDefault();
    const preset = prompt('Choose preset: 1) EURUSD 2) USDJPY 3) GBPUSD 4) EURJPY (enter 1-4)', '1');
    if (!preset) return;
    if (preset === '1') document.getElementById('pair').value = 'EURUSD';
    if (preset === '2') document.getElementById('pair').value = 'USDJPY';
    if (preset === '3') document.getElementById('pair').value = 'GBPUSD';
    if (preset === '4') document.getElementById('pair').value = 'EURJPY';
    hideError();
  });

  resetBtn.addEventListener('click', (e)=>{
    e.preventDefault();
    document.getElementById('pair').value = 'EURUSD';
    document.getElementById('convert_to').value = 'USD';
    document.getElementById('conversion_rate').value = '';
    hideError();
    resultsBox.innerHTML = `<p class="text-slate-400">Fill the pair and click Calculate. The pip value for 1 standard lot (100,000 base units) will be shown.</p>`;
  });

  calcBtn.addEventListener('click', async (e)=>{
    e.preventDefault();
    hideError();

    const pairRaw = document.getElementById('pair').value || '';
    const pair = pairRaw.replace(/[^A-Za-z]/g,'').toUpperCase();
    const convertToRaw = document.getElementById('convert_to').value || '';
    const convertTo = convertToRaw ? convertToRaw.toUpperCase() : '';
    const convRateRaw = document.getElementById('conversion_rate').value || '';
    const convRate = convRateRaw ? parseFloat(convRateRaw) : null;

    if (!pair || pair.length < 6) { showError('Please enter a valid pair like EURUSD or USDJPY.'); return; }

    // quick client-side predict
    const quote = pair.slice(-3);
    const pipSize = (quote === 'JPY') ? 0.01 : 0.0001;
    const pipMultiplier = (quote === 'JPY') ? 100 : 10000;
    const pipValueQuote = pipSize * 100000; // standard lot

    let localResult = {
      pair: pair,
      quote: quote,
      pip_size: pipSize,
      pip_multiplier: pipMultiplier,
      pip_value_quote: pipValueQuote,
      pip_value_currency: quote
    };

    // If conversion rate provided and convertTo given, show converted
    if (convertTo && convRate && convRate > 0) {
      localResult.pip_value_converted = pipValueQuote / convRate;
      localResult.converted_to = convertTo;
      localResult.conversion_rate_used = convRate;
    }

    // render local result quickly
    resultsBox.innerHTML = `
      <div class="grid gap-2">
        <div><strong>Pair:</strong> ${localResult.pair}</div>
        <div><strong>Pip size:</strong> ${localResult.pip_size}</div>
        <div><strong>Pip multiplier:</strong> ${localResult.pip_multiplier}</div>
        <div><strong>Pip value (per standard lot):</strong> ${formatNumber(localResult.pip_value_quote,6)} ${localResult.pip_value_currency}</div>
        ${localResult.pip_value_converted ? `<div><strong>Converted value:</strong> ${formatNumber(localResult.pip_value_converted,6)} ${localResult.converted_to}</div>` : ''}
      </div>
    `;

    // POST to server for verification
    try {
      const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
      const res = await fetch("{{ route('tools.pip_value.calc') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? {'X-CSRF-TOKEN': token} : {})
        },
        body: JSON.stringify({
          pair: pair,
          convert_to: convertTo || null,
          conversion_rate: convRate || null
        })
      });

      if (!res.ok) {
        const err = await res.json().catch(()=>({message:'Server error'}));
        if (err && err.error) showError(err.error);
        else showError(err.message || 'Server calculation failed');
        return;
      }

      const json = await res.json();
      // render verified response
      resultsBox.innerHTML = `
        <div class="grid gap-2">
          <div><strong>Pair:</strong> ${json.pair}</div>
          <div><strong>Pip size:</strong> ${json.pip_size}</div>
          <div><strong>Pip multiplier:</strong> ${json.pip_multiplier}</div>
          <div><strong>Pip value (per standard lot):</strong> ${formatNumber(json.pip_value_quote,6)} ${json.pip_value_currency}</div>
          ${json.pip_value_converted !== undefined ? `<div><strong>Converted value:</strong> ${formatNumber(json.pip_value_converted,6)} ${json.converted_to}</div>` : ''}
          ${json.conversion_rate_used ? `<div class="text-slate-400 text-sm mt-2">Conversion rate used: ${json.conversion_rate_used}</div>` : ''}
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

  // init default state
  document.getElementById('pair').value = 'EURUSD';
})();
</script>
@endpush
