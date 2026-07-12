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

   return view('welcome', compact(
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
    // 1. Fetch the upcoming events (assuming this is how you query them)
    $upcoming = \App\Models\Event::where('start_date', '>=', now())
                     ->orderBy('start_date', 'asc')
                     ->get();

    // 2. Safely grab RSVPs if the user is logged in, otherwise default to an empty collection
    $myRsvps = auth()->check()
        ? auth()->user()->rsvps()->pluck('event_id')
        : collect();

    // 3. Pass both to the view
    return view('public.events', compact('upcoming', 'myRsvps'));
}
}
