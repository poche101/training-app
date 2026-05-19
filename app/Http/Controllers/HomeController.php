<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Livestream;
use App\Models\Testimony;

class HomeController extends Controller
{

    // 1. Main Welcome/Landing Page
    public function index()
{
    $liveLivestream = Livestream::live()->latest()->first();   // ← changed
    $upcomingStream = Livestream::upcoming()->first();         // optional but useful
    $upcomingEvents = Event::upcoming()->take(6)->get();
    $announcements  = Announcement::latest()->take(6)->get();
    $testimonies    = Testimony::latest()->take(6)->get();

    return view('public.home', compact(
        'liveLivestream',
        'upcomingStream',
        'upcomingEvents',
        'announcements',
        'testimonies'
    ));
}
    // 2. Public Livestreams Directory (http://127.0.0.1:8000/livestreams)
   public function livestreams()
{
    $live        = Livestream::live()->latest()->first();
    $upcoming    = Livestream::upcoming()->take(5)->get();
    $past        = Livestream::where('status', 'ended')->latest()->paginate(12);

    // Fetch the missing variable!
    $livestreams = Livestream::latest()->paginate(15);

    // Pass it to the admin view
    return view('admin.livestreams.index', compact('live', 'upcoming', 'past', 'livestreams'));
}

    // 3. Single Stream Viewing Room
   // 3. Single Stream Viewing Room
    public function streamShow(Livestream $livestream)
    {
        // Update this line to match the file path we created earlier
        return view('public.livestream.show', compact('livestream'));
    }

    // 4. Public Events Directory
    public function events()
    {
        $upcoming = Event::upcoming()->get();
        $past     = Event::where('end_date', '<', now())->latest()->paginate(9);

        return view('public.events', compact('upcoming', 'past'));
    }
}
