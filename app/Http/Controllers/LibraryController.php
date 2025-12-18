<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibraryItem;

class LibraryController extends Controller
{
    public function index()
    {
        $items = LibraryItem::query()->orderBy('created_at','desc')->paginate(20);
        return view('library.index', compact('items'));
    }
}
