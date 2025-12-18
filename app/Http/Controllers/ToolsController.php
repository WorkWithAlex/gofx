<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;

class ToolsController extends Controller
{
    public function index()
    {
        $tools = Tool::query()->orderBy('order','asc')->get();
        return view('tools.index', compact('tools'));
    }

    /**
     * Display a tool page.
     * For certain tool slugs we return a custom view (interactive calculator).
     */
    public function show(string $tool)
    {
        $toolModel = Tool::where('slug', $tool)->firstOrFail();

        switch ($toolModel->slug) {
            case 'position-size-calculator':
                $viewFile = 'tools.position-size-calculator';
            break;
            case 'risk-reward-calculator':
                $viewFile = 'tools.risk-reward-calculator';
            break;
            case 'pip-value-calculator':
                $viewFile = 'tools.pip-value-calculator';
            break;
            case 'atr-calculator':
                $viewFile = 'tools.atr-calculator';
            break;
            case 'compounding-calculator':
                $viewFile = 'tools.compounding-calculator';
            break;
            default:
                $viewFile = 'tools.show';
            break;
        }

        return view($viewFile, ['tool' => $toolModel]);
    }

    /**
     * Server-side calculation endpoint for Position Size Calculator.
     * Accepts JSON or form data and returns JSON with calculated values.
     *
     * Expected inputs:
     *  - account_size (float) : account balance in currency (e.g. 10000)
     *  - risk_percent (float) : risk per trade as percent (e.g. 1 for 1%)
     *  - stop_pips (float)    : stop distance in pips (e.g. 50)
     *  - pip_value (float)    : pip value PER STANDARD LOT in account currency (optional)
     *
     * Response:
     *  - risk_amount
     *  - lots_standard
     *  - lots_mini
     *  - lots_micro
     *  - position_value (notional)
     */
    public function calculatePositionSize(\Illuminate\Http\Request $request){
        $data = $request->validate([
            'account_size' => ['required','numeric','min:0'],
            'risk_percent' => ['required','numeric','min:0'],
            'stop_pips'    => ['required','numeric','min:0.0001'],
            'pip_value'    => ['nullable','numeric','min:0.000001'],
        ]);

        $accountSize = (float) $data['account_size'];
        $riskPercent = (float) $data['risk_percent'];
        $stopPips = (float) $data['stop_pips'];
        $pipValue = isset($data['pip_value']) && $data['pip_value'] > 0 ? (float)$data['pip_value'] : null;

        // compute risk amount
        $riskAmount = ($accountSize * ($riskPercent / 100.0));

        // If pip_value not provided, use a reasonable default (USD quote approx for common pairs)
        // Default pip value per standard lot approximations:
        // - For pairs quoted in USD (e.g. EURUSD), pip value per standard lot is ~10 USD.
        // - For USD/JPY the pip is 0.01 and value ~9.13 for example; we keep 10 as safe simple default.
        if (empty($pipValue)) {
            $pipValue = 10.0;
        }

        // Avoid division by zero
        if ($stopPips <= 0 || $pipValue <= 0) {
            return response()->json(['error' => 'Invalid stop distance or pip value'], 422);
        }

        // Lots (standard lot units) = risk_amount / (stop_pips * pip_value_per_standard_lot)
        $lotsStandard = $riskAmount / ($stopPips * $pipValue);

        // Clamp to sensible minimum (0)
        $lotsStandard = max(0, $lotsStandard);

        $result = [
            'account_size' => round($accountSize, 2),
            'risk_percent' => round($riskPercent, 4),
            'risk_amount'  => round($riskAmount, 4),
            'stop_pips'    => round($stopPips, 4),
            'pip_value'    => round($pipValue, 6),
            'lots_standard'=> round($lotsStandard, 6),
            'lots_mini'    => round($lotsStandard * 10, 6),   // 1 standard = 10 mini
            'lots_micro'   => round($lotsStandard * 100, 6),  // 1 standard = 100 micro
            'notional'     => round($lotsStandard * 100000 * ($pipValue / 10), 2), // approximate notional (useful)
        ];

        return response()->json($result);
    }

    /**
     * Server-side calculation endpoint for Risk/Reward Calculator.
     *
     * Inputs:
     *  - entry_price (required, numeric)
     *  - stop_price (required, numeric)
     *  - take_profit_price (required, numeric)
     *  - pip_multiplier (required, numeric) e.g. 10000 for 4-decimal pairs, 100 for JPY pairs
     *  - pip_value (nullable, numeric) pip value per standard lot in account currency (default 10)
     *  - lots (nullable, numeric) number of standard lots (if provided returns monetary values)
     *
     * Response (json):
     *  - stop_pips, reward_pips, rr_ratio, risk_amount (if lots), reward_amount (if lots), required_winrate_percent
     */
    public function calculateRiskReward(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'entry_price' => ['required','numeric'],
            'stop_price' => ['required','numeric'],
            'take_profit_price' => ['required','numeric'],
            'pip_multiplier' => ['required','numeric','min:1'],
            'pip_value' => ['nullable','numeric','min:0.000001'],
            'lots' => ['nullable','numeric','min:0'],
        ]);

        $entry = (float) $data['entry_price'];
        $stop = (float) $data['stop_price'];
        $tp = (float) $data['take_profit_price'];
        $pipMultiplier = (float) $data['pip_multiplier'];
        $pipValue = isset($data['pip_value']) && $data['pip_value'] > 0 ? (float)$data['pip_value'] : 10.0;
        $lots = isset($data['lots']) ? (float)$data['lots'] : null;

        // pips are absolute difference multiplied by pipMultiplier
        $stopPips = abs($entry - $stop) * $pipMultiplier;
        $rewardPips = abs($tp - $entry) * $pipMultiplier;

        if ($stopPips <= 0) {
            return response()->json(['error' => 'Invalid stop pips (computed 0). Check entry and stop prices.'], 422);
        }

        // Ratio reward/risk
        $rr = $rewardPips / $stopPips;

        // Monetary amounts (if lots provided)
        $riskAmount = null;
        $rewardAmount = null;
        if (!is_null($lots) && $lots > 0) {
            // For standard lot: monetary risk = lots * stopPips * pipValue
            $riskAmount = $lots * $stopPips * $pipValue;
            $rewardAmount = $lots * $rewardPips * $pipValue;
        }

        // required winrate to breakeven = 1 / (1 + RR)
        // but if RR is 0, required winrate is 100%
        $requiredWinrate = $rr > 0 ? (1 / (1 + $rr)) * 100 : 100;

        $result = [
            'entry_price' => $entry,
            'stop_price' => $stop,
            'take_profit_price' => $tp,
            'stop_pips' => round($stopPips, 4),
            'reward_pips' => round($rewardPips, 4),
            'rr_ratio' => round($rr, 4),
            'pip_value' => round($pipValue, 6),
            'lots' => $lots !== null ? round($lots, 6) : null,
            'risk_amount' => is_null($riskAmount) ? null : round($riskAmount, 4),
            'reward_amount' => is_null($rewardAmount) ? null : round($rewardAmount, 4),
            'required_winrate_percent' => round($requiredWinrate, 2),
        ];

        return response()->json($result);
    }

    /**
     * Server-side endpoint to compute pip value for a standard lot.
     *
     * Inputs:
     *  - pair (string, required) e.g. "EURUSD", "USDJPY", "EURJPY"
     *  - conversion_rate (nullable, numeric) : optional rate to convert quote-currency pip value to account currency
     *       (for example, to convert JPY pip value to USD, pass USDJPY rate: 150 -> pip_value_usd = pip_value_jpy / 150)
     *
     * Response:
     *  - pair, pip_size, pip_multiplier, pip_value_quote (long), quote_currency, pip_value_converted (if conversion_rate provided), converted_to (if provided)
     */
    public function calculatePipValue(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'pair' => ['required','string','max:12'],
            'conversion_rate' => ['nullable','numeric','min:0.000001'],
            'convert_to' => ['nullable','string','max:10'], // e.g. 'USD'
        ]);

        // normalize pair (upper, remove / or -)
        $raw = strtoupper(trim($data['pair']));
        $pair = str_replace(['/', '-', ' '], '', $raw);

        if (strlen($pair) < 6) {
            return response()->json(['error' => 'Invalid pair format. Use e.g. EURUSD or USDJPY.'], 422);
        }

        // naive split: base = first 3 chars, quote = last 3 chars (works for common 6-char pairs)
        $base = substr($pair, 0, 3);
        $quote = substr($pair, -3);

        // pip size: JPY pairs use 0.01, otherwise 0.0001
        $pipSize = ($quote === 'JPY') ? 0.01 : 0.0001;
        $pipMultiplier = ($quote === 'JPY') ? 100 : 10000;

        // pip value in quote currency for 1 standard lot (100,000 base units):
        // pipValue_quote = pipSize * lotSize (lotSize = 100000)
        $lotSize = 100000;
        $pipValueQuote = $pipSize * $lotSize; // e.g., EURUSD -> 0.0001 * 100000 = 10 (USD)

        $response = [
            'pair' => $pair,
            'base' => $base,
            'quote' => $quote,
            'pip_size' => $pipSize,
            'pip_multiplier' => $pipMultiplier,
            'pip_value_quote' => round($pipValueQuote, 6),
            'pip_value_currency' => $quote,
        ];

        // if conversion_rate provided, convert pip value from quote currency to target account currency
        if (isset($data['conversion_rate']) && $data['conversion_rate'] > 0 && !empty($data['convert_to'])) {
            // conversion_rate meaning:
            // if convert_to = 'USD' and quote='JPY' and conversion_rate = USDJPY (e.g. 150.00),
            // then pip_value_in_USD = pip_value_in_JPY / USDJPY_rate
            // For a more general case the user should supply the correct conversion_rate that maps target currency.
            $conv = (float) $data['conversion_rate'];
            $converted = $pipValueQuote / $conv;

            $response['pip_value_converted'] = round($converted, 6);
            $response['converted_to'] = strtoupper($data['convert_to']);
            $response['conversion_rate_used'] = round($conv, 8);
        }

        return response()->json($response);
    }

    /**
     * Server-side endpoint to compute ATR (Wilder's method).
     *
     * Inputs:
     *  - candles (string) : newline separated lines of "high,low,close"
     *      e.g.
     *      1.1234,1.1200,1.1210
     *      1.1250,1.1220,1.1240
     *  - period (int) : ATR period, default 14
     *
     * Response:
     *  - period, count_candles, tr_series (array), atr_series (array), atr_last
     */
    public function calculateAtr(\Illuminate\Http\Request $request){
        $data = $request->validate([
            'candles' => ['required','string'],
            'period' => ['required','integer','min:1'],
        ]);

        $period = (int) $data['period'];
        $raw = trim($data['candles']);

        // parse lines
        $lines = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $raw)));
        $candles = [];

        foreach ($lines as $idx => $line) {
            // allow comma or whitespace separated values
            $parts = preg_split('/[,\s]+/', trim($line));
            if (count($parts) < 3) {
                return response()->json(['error' => "Invalid candle on line ".($idx+1).". Expected high,low,close."], 422);
            }
            $h = (float) $parts[0];
            $l = (float) $parts[1];
            $c = (float) $parts[2];
            // basic sanity
            if ($h < $l) {
                // swap if out of order
                [$h, $l] = [$l, $h];
            }
            $candles[] = ['high' => $h, 'low' => $l, 'close' => $c];
        }

        $count = count($candles);
        if ($count < $period) {
            return response()->json(['error' => "Need at least {$period} candles to compute ATR (you provided {$count})."], 422);
        }

        // compute TRs
        $trs = [];
        for ($i = 0; $i < $count; $i++) {
            $high = $candles[$i]['high'];
            $low = $candles[$i]['low'];
            $prevClose = ($i > 0) ? $candles[$i-1]['close'] : null;

            $trCandidates = [];
            $trCandidates[] = $high - $low;
            if (!is_null($prevClose)) {
                $trCandidates[] = abs($high - $prevClose);
                $trCandidates[] = abs($low - $prevClose);
            }
            $trs[] = round(max($trCandidates), 8);
        }

        // compute ATR series using Wilder's smoothing:
        // first ATR = simple average of first N TRs (indexes 0..N-1)
        // subsequent ATR = (prev_atr * (N-1) + current_tr) / N
        $atrs = [];
        // we can only start ATR at index = period-1
        $firstWindow = array_slice($trs, 0, $period);
        $firstAtr = array_sum($firstWindow) / $period;
        $atrs[$period-1] = round($firstAtr, 8);

        // continue
        for ($i = $period; $i < $count; $i++) {
            $prevAtr = $atrs[$i-1] ?? $firstAtr;
            $currentTr = $trs[$i];
            $atr = (($prevAtr * ($period - 1)) + $currentTr) / $period;
            $atrs[$i] = round($atr, 8);
        }

        // Prepare ATR series aligned to candle index: null where not computed yet
        $atr_series_aligned = array_fill(0, $count, null);
        foreach ($atrs as $idx => $val) {
            $atr_series_aligned[$idx] = $val;
        }

        $atr_last = null;
        // find last non-null ATR from end
        for ($i = $count - 1; $i >= 0; $i--) {
            if (!is_null($atr_series_aligned[$i])) {
                $atr_last = $atr_series_aligned[$i];
                break;
            }
        }

        return response()->json([
            'period' => $period,
            'count_candles' => $count,
            'tr_series' => $trs,
            'atr_series' => $atr_series_aligned,
            'atr_last' => $atr_last,
        ]);
    }

    /**
     * Server-side endpoint to compute compounding results.
     *
     * Inputs:
     *  - initial_balance (required, numeric, >= 0)
     *  - periodic_contribution (nullable, numeric, >= 0) // contribution per period (e.g., monthly)
     *  - annual_return_percent (required, numeric) // e.g. 12 for 12% annual
     *  - years (required, numeric, > 0) // total time in years
     *  - compounding_per_year (required, integer >=1) // e.g. 12 for monthly, 1 for yearly
     *  - contribution_timing (optional, string: 'end'|'begin', default 'end') // when contributions are added
     *
     * Response:
     *  - summary: final_balance, total_contributed, total_gain, periods, period_rate
     *  - series: array of period-by-period entries: {period_index, balance, contribution_this_period, cumulative_contributed, gain}
     */
    public function calculateCompounding(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'initial_balance' => ['required','numeric','min:0'],
            'periodic_contribution' => ['nullable','numeric','min:0'],
            'annual_return_percent' => ['required','numeric'],
            'years' => ['required','numeric','min:0.0001'],
            'compounding_per_year' => ['required','integer','min:1'],
            'contribution_timing' => ['nullable','in:end,begin'],
        ]);

        $P0 = (float) $data['initial_balance'];
        $PMT = isset($data['periodic_contribution']) ? (float)$data['periodic_contribution'] : 0.0;
        $annualRatePct = (float) $data['annual_return_percent'];
        $years = (float) $data['years'];
        $n = (int) $data['compounding_per_year'];
        $timing = $data['contribution_timing'] ?? 'end';

        $r = $annualRatePct / 100.0;
        $periods = (int) round($n * $years);
        if ($periods <= 0) {
            return response()->json(['error' => 'Total periods computed as 0. Check years and compounding_per_year.'], 422);
        }

        $periodRate = $r / $n; // rate per period (decimal)

        // We will compute series period-by-period.
        $series = [];
        $balance = $P0;
        $cumulative = 0.0;

        for ($i = 1; $i <= $periods; $i++) {
            // If contribution timing is 'begin', contribution is applied at start of period before interest
            if ($timing === 'begin' && $PMT > 0) {
                $balance += $PMT;
                $cumulative += $PMT;
            }

            // apply interest for this period
            $balance = $balance * (1 + $periodRate);

            // If contribution timing is 'end', contribution applied after interest
            if ($timing === 'end' && $PMT > 0) {
                $balance += $PMT;
                $cumulative += $PMT;
            }

            $gain = $balance - $P0 - $cumulative;

            $series[] = [
                'period_index' => $i,
                'balance' => round($balance, 8),
                'contribution_this_period' => $PMT > 0 ? round($PMT, 8) : 0.0,
                'cumulative_contributed' => round($cumulative, 8),
                'gain' => round($gain, 8),
            ];
        }

        $finalBalance = end($series)['balance'] ?? $P0;
        $totalContributed = $cumulative;
        $totalGain = round($finalBalance - $P0 - $totalContributed, 8);

        return response()->json([
            'summary' => [
                'initial_balance' => round($P0, 8),
                'final_balance' => round($finalBalance, 8),
                'total_contributed' => round($totalContributed, 8),
                'total_gain' => round($totalGain, 8),
                'periods' => $periods,
                'period_rate' => round($periodRate, 12),
                'annual_return_percent' => $annualRatePct,
                'years' => $years,
                'compounding_per_year' => $n,
                'contribution_timing' => $timing,
            ],
            'series' => $series,
        ]);
    }


}
