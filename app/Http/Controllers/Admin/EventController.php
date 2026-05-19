<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    public function index()
    {
        $events = Event::withCount(['rsvps', 'attendances'])->with('creator')->latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'location'      => ['nullable', 'string', 'max:255'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after:start_date'],
            'requires_rsvp' => ['boolean'],
            'max_attendees' => ['nullable', 'integer', 'min:1'],
        ]);

        $data['requires_rsvp'] = $request->boolean('requires_rsvp');
        $data['created_by']    = auth()->id();

        $event = Event::create($data);
        ActivityLog::record('Created Event', "Created: {$event->title}", $event);

        return redirect()->route('admin.events.index')
                         ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $attendees = Attendance::where('event_id', $event->id)->with('user')->latest()->get();
        $rsvps     = $event->rsvps()->with('user')->get();

        return view('admin.events.show', compact('event', 'attendees', 'rsvps'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'location'      => ['nullable', 'string', 'max:255'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after:start_date'],
            'requires_rsvp' => ['boolean'],
            'max_attendees' => ['nullable', 'integer', 'min:1'],
        ]);

        $data['requires_rsvp'] = $request->boolean('requires_rsvp');
        $event->update($data);

        ActivityLog::record('Updated Event', "Updated: {$event->title}", $event);

        return redirect()->route('admin.events.index')
                         ->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        ActivityLog::record('Deleted Event', "Deleted: {$event->title}");
        $event->delete();

        return redirect()->route('admin.events.index')
                         ->with('success', 'Event deleted.');
    }
}
