<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibraryItem;

class LibraryItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'title' => 'Forex Glossary PDF',
                'slug' => 'forex-glossary-pdf',
                'type' => 'pdf',
                'file_path' => 'storage/library/forex-glossary.pdf', // ensure you upload a file here if you want
                'file_name' => 'forex-glossary.pdf',
                'file_size' => null,
                'summary' => 'A compact glossary of common forex terms for beginners.',
                'public' => true,
            ],
            [
                'title' => 'Position Sizing Cheat Sheet',
                'slug' => 'position-sizing-cheat-sheet',
                'type' => 'cheatsheet',
                'file_path' => null,
                'url' => 'https://example.com/position-sizing-cheat-sheet',
                'summary' => 'Quick reference for calculating position size and risk per trade.',
                'public' => true,
            ],
            [
                'title' => 'Top 10 Trading Mistakes (Video)',
                'slug' => 'top-10-trading-mistakes-video',
                'type' => 'video',
                'url' => 'https://youtube.com/your-video', // replace with real link
                'summary' => 'Short video describing common trading errors and how to avoid them.',
                'public' => true,
            ],
        ];

        foreach ($items as $i) {
            LibraryItem::updateOrCreate(['slug' => $i['slug']], $i);
        }
    }
}
