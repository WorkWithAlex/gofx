<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;

class GuidesController extends Controller
{
    /**
     * Display a paginated list of published guides.
     */
    public function index(Request $request)
    {
        $guides = Guide::published()
                        ->orderBy('published_at', 'desc')
                        ->paginate(12);

        return view('guides.index', compact('guides'));
    }

    /**
     * Display a single guide by slug.
     */
    public function show(string $slug)
    {
        $guide = Guide::where('slug', $slug)->firstOrFail();

        // If you want to restrict to published only:
        if (! $guide->published) {
            abort(404);
        }

        return view('guides.show', compact('guide'));
    }
}
