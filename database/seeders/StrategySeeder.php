<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Strategy;

class StrategySeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $items = [
            [
                'title' => 'Simple Breakout Strategy',
                'slug' => 'simple-breakout-strategy',
                'excerpt' => 'A straightforward breakout system for intraday traders with clear entry and exits.',
                'body' => '<p>Entry on breakout, set stop below breakout candle and manage risk with a trailing stop.</p>',
                'author' => 'GOFX',
                'published' => true,
                'published_at' => $now,
            ],
            [
                'title' => 'Mean Reversion Pullback',
                'slug' => 'mean-reversion-pullback',
                'excerpt' => 'A mean-reversion approach using ATR to define targets and stops.',
                'body' => '<p>Uses ATR and confluence zones for higher probability pullback entries.</p>',
                'author' => 'GOFX',
                'published' => true,
                'published_at' => $now->copy()->subDay(),
            ],
            [
                'title' => 'Momentum Trend Following',
                'slug' => 'momentum-trend-following',
                'excerpt' => 'A trend-following strategy that combines moving averages with price action confirmation.',
                'body' => '<p>Ride strong trends with proper risk management and scaling techniques.</p>',
                'author' => 'Tehseen Shaikh',
                'published' => true,
                'published_at' => $now->copy()->subDays(3),
            ],
        ];

        foreach ($items as $it) {
            Strategy::updateOrCreate(['slug' => $it['slug']], $it);
        }
    }
}
