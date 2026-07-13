<?php

// app/Http/Controllers/LivestreamController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Livestream;

class LivestreamController extends Controller
{
    public function index()
    {
        // ✅ Works on SQLite and MySQL
        $live = Livestream::where('status', 'live')
            ->orderBy('started_at', 'desc')
            ->first();

        $upcoming = Livestream::where('status', 'scheduled')
            ->orderBy('scheduled_at')
            ->get();

        $past = Livestream::where('status', 'ended')
            ->orderBy('started_at', 'desc')
            ->paginate(9);

        return view('public.livestream.index', compact('live', 'upcoming', 'past'));
    }

    public function show(Livestream $livestream)
    {
        return view('public.livestream.show', compact('livestream'));
    }
}
