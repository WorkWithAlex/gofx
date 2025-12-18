<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tool;

class ToolSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'title' => 'Position Size Calculator',
                'slug' => 'position-size-calculator',
                'summary' => 'Calculate position size based on risk and stop loss.',
                'order' => 10,
            ],
            [
                'title' => 'Risk/Reward Calculator',
                'slug' => 'risk-reward-calculator',
                'summary' => 'Quick risk-reward calculator for trade planning.',
                'order' => 20,
            ],
            [
                'title' => 'Pip Value Calculator',
                'slug' => 'pip-value-calculator',
                'summary' => 'Find pip value for currency pairs and lot sizes.',
                'order' => 30,
            ],
            [
                'title' => 'ATR Calculator',
                'slug' => 'atr-calculator',
                'summary' => 'Average True Range (ATR) helper for volatility measurement.',
                'order' => 40,
            ],
            [
                'title' => 'Compounding Calculator',
                'slug' => 'compounding-calculator',
                'summary' => 'Compound growth calculator for position sizing strategies.',
                'order' => 50,
            ],
        ];

        foreach ($items as $i) {
            Tool::updateOrCreate(['slug' => $i['slug']], $i);
        }
    }
}
