<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * WhatsApp Trading Room
     */
    public function whatsappRoom()
    {
        return view('community.whatsapp-room');
    }

    /**
     * Prime membership / paid community page (info + benefits)
     */
    public function prime()
    {
        return view('community.prime');
    }

    /**
     * Student Success Wall - showcase students and testimonials
     */
    public function successWall()
    {
        return view('community.success-wall');
    }
}
