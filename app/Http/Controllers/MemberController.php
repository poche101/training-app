<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\Livestream;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    // The __construct block calling $this->middleware() has been completely removed to fix the Laravel crash.

    public function dashboard()
    {
        $user           = Auth::user();
        $liveLivestream = Livestream::live()->latest()->first();
        $announcements  = Announcement::published()->orderBy('is_pinned', 'desc')->latest()->take(5)->get();
        $upcomingEvents = Event::upcoming()->take(4)->get();
        $recentResources = Resource::where('is_public', true)->latest()->take(4)->get();

        return view('member.dashboard', compact(
            'user', 'liveLivestream', 'announcements', 'upcomingEvents', 'recentResources'
        ));
    }

    public function resources(Request $request)
    {
        $query = Resource::where('is_public', true)->with('category');

        if ($request->search) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $resources   = $query->latest()->paginate(12);
        $categories  = \App\Models\ResourceCategory::withCount('resources')->get();

        return view('member.resources', compact('resources', 'categories'));
    }

    public function downloadResource(Resource $resource)
    {
        if (!$resource->is_public && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $resource->incrementDownload();
        return redirect($resource->file_url);
    }

    public function announcements()
    {
        $announcements = Announcement::published()
                            ->orderBy('is_pinned', 'desc')
                            ->latest()
                            ->paginate(15);

        return view('member.announcements', compact('announcements'));
    }

    public function events()
    {
        $upcoming = Event::upcoming()->get();
        $myRsvps  = EventRsvp::where('user_id', auth()->id())->pluck('event_id');

        return view('public.events', compact('upcoming', 'myRsvps'));
    }

    public function rsvpEvent(Event $event)
    {
        EventRsvp::updateOrCreate(
            ['user_id' => auth()->id(), 'event_id' => $event->id],
            ['status' => 'attending']
        );

        return back()->with('success', 'You have successfully RSVP\'d for this event!');
    }

    public function checkinEvent(Event $event)
    {
        \App\Models\Attendance::firstOrCreate(
            ['user_id' => auth()->id(), 'event_id' => $event->id],
            ['checked_in_at' => now()]
        );

        return back()->with('success', 'Check-in successful!');
    }

    public function offerings()
    {
        $offerings = \App\Models\Offering::where('is_active', true)->get();
        return view('member.offerings', compact('offerings'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('member.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($request->only('full_name', 'phone'));

        return back()->with('success', 'Profile updated successfully!');
    }
}
