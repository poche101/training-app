<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->paginate(15);
        return view('admin.announcement.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcement.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'type'         => ['required', 'in:general,urgent,event'],
            'is_pinned'    => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['is_pinned']   = $request->boolean('is_pinned');
        $data['published_at'] = $request->published_at ?? now();
        $data['created_by']  = auth()->id();

        $announcement = Announcement::create($data);
        ActivityLog::record('Created Announcement', "Created: {$announcement->title}", $announcement);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Announcement published.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'type'         => ['required', 'in:general,urgent,event'],
            'is_pinned'    => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['is_pinned'] = $request->boolean('is_pinned');
        $announcement->update($data);

        ActivityLog::record('Updated Announcement', "Updated: {$announcement->title}", $announcement);

        return back()->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        ActivityLog::record('Deleted Announcement', "Deleted: {$announcement->title}");
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Announcement deleted.');
    }
}
