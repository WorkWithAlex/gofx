<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $items = [
            [
                'title' => 'Introduction to Forex: What Every Beginner Must Know',
                'slug' => 'introduction-to-forex-what-every-beginner-must-know',
                'excerpt' => 'A practical primer to forex markets: currency pairs, session times, lot sizes and basic terminology to get you started.',
                'body' => '<h2>What is Forex?</h2>
<p>Forex (foreign exchange) is the decentralised market where currencies are traded. Traders speculate on the value of one currency relative to another — for example EUR/USD. The market runs 24 hours across global sessions (Asia, Europe, US), and understanding session overlap is crucial for volatility and liquidity planning.</p>

<h2>Key concepts</h2>
<ul>
  <li><strong>Pairs &amp; base/quote:</strong> EUR/USD — EUR is the base, USD the quote.</li>
  <li><strong>Lots &amp; contract size:</strong> Standard, mini, micro. Lot size determines pip value and position exposure.</li>
  <li><strong>Leverage &amp; margin:</strong> Amplifies returns and risk — use prudently.</li>
  <li><strong>Bid/Ask &amp; spread:</strong> The cost to enter a trade — choose liquid pairs for tight spreads.</li>
</ul>

<p>This article gives you the vocabulary and high-level map so you can read charts and start experimenting on a demo account.</p>',
                'author' => 'Tehseen Shaikh',
                'meta_description' => 'Beginner friendly introduction to forex markets, pairs, lot sizes and essential trading vocabulary.',
                'published' => true,
                'published_at' => $now,
            ],

            [
                'title' => 'Reading Market Structure: Swings, Breaks & High Probability Areas',
                'slug' => 'reading-market-structure-swings-breaks-high-probability-areas',
                'excerpt' => 'How market structure forms the backbone of price action — identify trends, structure breaks and zones that matter.',
                'body' => '<h2>Market Structure Basics</h2>
<p>Market structure is the sequence of highs and lows that price makes on a chart. In simple terms:</p>
<ul>
  <li><strong>Uptrend:</strong> Higher highs &amp; higher lows.</li>
  <li><strong>Downtrend:</strong> Lower highs &amp; lower lows.</li>
  <li><strong>Structure break:</strong> When price invalidates the last swing low/high — a clue that momentum changed.</li>
</ul>

<h2>Finding high-probability areas</h2>
<p>Combine structure with order flow clues — prior swing highs/lows, consolidation ranges, and liquidity pools. These areas produce the best risk/reward setups when price returns to them with evidence of acceptance or rejection.</p>

<h2>Practical tip</h2>
<p>Draw only the most meaningful swing points and watch price reaction when it revisits the area. A tight stop just beyond the invalidation point keeps risk controlled.</p>',
                'author' => 'Tehseen Shaikh',
                'meta_description' => 'Learn to read market structure and identify high probability trade areas using swings and structure breaks.',
                'published' => true,
                'published_at' => $now->copy()->subDay(),
            ],

            [
                'title' => 'Risk Management Essentials — Protecting Your Trading Capital',
                'slug' => 'risk-management-essentials-protecting-your-trading-capital',
                'excerpt' => 'Position sizing, stop placement, and rules to keep you trading another day. Risk management beats edge every time.',
                'body' => '<h2>Why risk management matters</h2>
<p>Even a profitable strategy fails with poor risk management. Your edge only works if you survive drawdowns and manage position sizes sensibly.</p>

<h2>Core rules</h2>
<ul>
  <li><strong>Risk per trade:</strong> Define a fixed % of capital (e.g., 0.5–2%).</li>
  <li><strong>Use stop losses:</strong> Always define invalidation — where your trade idea is wrong.</li>
  <li><strong>Reward-to-risk:</strong> Aim for setups that justify the probability (e.g., 2:1 or better).</li>
  <li><strong>Max drawdown:</strong> Set an acceptable max and step back when reached.</li>
</ul>

<h2>Tools</h2>
<p>Use calculators (position size, pip value, compounding) to convert risk rules into lot sizes. We will provide interactive tools in the Learn Hub to make this effortless.</p>',
                'author' => 'GOFX Team',
                'meta_description' => 'Position sizing, stop placement and rules to protect trading capital and survive drawdowns.',
                'published' => true,
                'published_at' => $now->copy()->subDays(2),
            ],

            [
                'title' => 'Using ATR (Average True Range) to Measure Volatility',
                'slug' => 'using-atr-to-measure-volatility',
                'excerpt' => 'ATR helps define stop distances, target ranges and position sizing by capturing market volatility.',
                'body' => '<h2>What ATR tells you</h2>
<p>ATR measures average volatility over a chosen period (commonly 14). It is not directional — it quantifies movement. Higher ATR means wider stops or smaller position sizes; lower ATR means tighter stops or larger sizes.</p>

<h2>Practical uses</h2>
<ul>
  <li><strong>Stop placement:</strong> Use ATR multiples (e.g., 1.5x ATR) to place volatility-aware stops.</li>
  <li><strong>Position sizing:</strong> Combine ATR with your risk per trade to calculate lot sizes that account for volatility.</li>
  <li><strong>Targets &amp; trailing:</strong> ATR-based trailing stops adapt to market conditions.</li>
</ul>

<p>We’ll include an ATR calculator in the Tools section so you can experiment with ATR-based stops and position sizes.</p>',
                'author' => 'Tehseen Shaikh',
                'meta_description' => 'How to use ATR for stop placement, position sizing and trailing mechanisms.',
                'published' => true,
                'published_at' => $now->copy()->subDays(3),
            ],

            [
                'title' => 'Position Sizing & Compounding: A Practical Guide',
                'slug' => 'position-sizing-and-compounding-practical-guide',
                'excerpt' => 'Convert your risk rules into actionable lot sizes and learn how compounding affects growth over time.',
                'body' => '<h2>From % risk to lot size</h2>
<p>Start by deciding what percent of your account you will risk per trade. Combine that with your stop distance (in pips) and the pip value to compute lot size. Our Position Size calculator automates these calculations for you.</p>

<h2>Compounding basics</h2>
<p>Compounding grows capital by reinvesting profits. A small, consistent edge compounded over time can produce significant growth — but it increases drawdown risk if you up-size too quickly. Use conservative step-ups and test with historical simulations.</p>

<h2>Example</h2>
<p>If you risk 1% of a $10,000 account and the stop requires 50 pips with a pip value of $1 per micro lot, your position size math will determine how many lots you can place without exceeding the 1% rule.</p>',
                'author' => 'GOFX Team',
                'meta_description' => 'How to calculate position size from risk and use compounding safely to grow trading capital.',
                'published' => true,
                'published_at' => $now->copy()->subDays(4),
            ],
        ];

        foreach ($items as $i) {
            Article::updateOrCreate(['slug' => $i['slug']], $i);
        }
    }
}
