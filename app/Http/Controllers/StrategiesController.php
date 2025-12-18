<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use Illuminate\Http\Request;

class StrategiesController extends Controller
{
    /**
     * Display a paginated list of published strategies.
     */
    public function index(Request $request)
    {
        $strategies = Strategy::published()
                               ->orderBy('published_at', 'desc')
                               ->paginate(12);

        return view('strategies.index', compact('strategies'));
    }

    /**
     * Show a single strategy by slug.
     */
    public function show(string $slug)
    {
        $strategy = Strategy::where('slug', $slug)->firstOrFail();

        if (! $strategy->published) {
            abort(404);
        }

        return view('strategies.show', compact('strategy'));
    }
}
