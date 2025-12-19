<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Allowed course slugs and their corresponding view names.
     * Keep this in sync with resources/views/courses/*.blade.php
     */
    private array $courses = [
        'forex-mastery'            => 'courses.forex-mastery',
        'price-action'             => 'courses.price-action-market-structure',
        'intraday-swing'           => 'courses.intraday-swing-trading',
        // 'advanced-psychology'      => 'courses.advanced-trading-psychology',
        'smart-money-concepts'      => 'courses.smart-money-concepts',
        'test'                      => 'courses.test',
    ];

    /**
     * Show the courses listing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Pass a small list to the view — useful for dynamic menus
        $list = collect($this->courses)->mapWithKeys(fn($v, $k) => [$k => [
            'slug' => $k,
            'title' => $this->titleFromSlug($k),
            'view' => $v,
            'thumbnail' => "storage/images/courses/{$k}.jpg",
        ]])->values();

        return view('courses.index', [
            'courses' => $list,
        ]);
    }

    /**
     * Show an individual course page by slug.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(string $slug, int $price = 1)
    {
        // Normalize slug
        $slug = trim($slug);
        $price = (int)$price;

        if (! array_key_exists($slug, $this->courses)) {
            abort(404);
        }

        $view = $this->courses[$slug];

        // Pass some metadata to the view (useful for breadcrumbs / meta tags)
        $meta = [
            'slug'  => $slug,
            'title' => $this->titleFromSlug($slug),
        ];

        return view($view, compact('meta', 'price'));
    }

    /**
     * Helper: make a human-friendly title from slug.
     */
    private function titleFromSlug(string $slug): string
    {
        return match ($slug) {
            'forex-mastery' => 'Forex Mastery',
            'price-action' => 'Price Action & Market Structure',
            'intraday-swing' => 'Intraday & Swing Trading',
            'advanced-psychology' => 'Advanced Trading Psychology',
            default => ucwords(str_replace(['-', '_'], ' ', $slug)),
        };
    }
}
