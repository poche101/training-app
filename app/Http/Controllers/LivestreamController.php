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
$livestreams = Livestream::whereIn('status', ['live', 'scheduled'])
    ->orderByRaw("CASE status WHEN 'live' THEN 0 WHEN 'scheduled' THEN 1 ELSE 2 END")
    ->orderBy('scheduled_at')
    ->get();

        return view('public.livestream.index', compact('livestreams'));
    }

    public function show(Livestream $livestream)
    {
        return view('public.livestream.show', compact('livestream'));
    }
}
