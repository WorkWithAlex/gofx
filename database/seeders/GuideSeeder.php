<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guide;
use Illuminate\Support\Str;

class GuideSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $items = [
            [
                'title' => 'Getting Started with Forex',
                'slug' => 'getting-started-with-forex',
                'excerpt' => 'A beginner-friendly guide to forex markets, basic terminology and setting up your first trade.',
                'body' => '<p>This guide covers the basics of forex trading, currency pairs, lot sizes and the trading day.</p>',
                'author' => 'Tehseen Shaikh',
                'meta_description' => 'Beginner guide to forex markets and how to start trading.',
                'published' => true,
                'published_at' => $now,
            ],
            [
                'title' => 'Reading Market Structure',
                'slug' => 'reading-market-structure',
                'excerpt' => 'Understand swings, structure breaks, and how to identify high probability areas on the chart.',
                'body' => '<p>Market structure forms the backbone of price action trading. Learn to identify swings and structure breaks.</p>',
                'author' => 'Tehseen Shaikh',
                'meta_description' => 'How to read market structure and use it for decision making.',
                'published' => true,
                'published_at' => $now->copy()->subDay(),
            ],
            [
                'title' => 'Intro to Risk Management',
                'slug' => 'intro-to-risk-management',
                'excerpt' => 'How to size positions, set stops and protect capital as a new trader.',
                'body' => '<p>Risk management is the single most important skill. This guide walks through position sizing basics.</p>',
                'author' => 'GOFX Team',
                'meta_description' => 'Position sizing and risk controls for retail traders.',
                'published' => true,
                'published_at' => $now->copy()->subDays(2),
            ],
        ];

        foreach ($items as $i) {
            Guide::updateOrCreate(['slug' => $i['slug']], $i);
        }
    }
}
