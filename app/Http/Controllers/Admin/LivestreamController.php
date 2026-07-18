<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Livestream;
use Illuminate\Http\Request;
use App\Models\StreamComment;


class LivestreamController extends Controller
{
    // ─── REMOVED LEGACY CONSTRUCTOR ────────────────────────────────────
    // Middleware is already handled securely at the routing layer!

    public function index()
    {
        $livestreams = Livestream::with('creator')->latest()->paginate(15);
        return view('admin.livestreams.index', compact('livestreams'));
    }

    public function create()
    {
        return view('admin.livestreams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'stream_url'   => ['nullable', 'url'],
            'platform'     => ['required', 'in:youtube,vimeo,rtmp'],
            'scheduled_at' => ['nullable', 'date'],
            'thumbnail'    => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data['status']     = 'scheduled';
        $data['created_by'] = auth()->id();

        $stream = Livestream::create($data);

        ActivityLog::record('Created Livestream', "Created stream: {$stream->title}", $stream);

        return redirect()->route('admin.livestreams.index')
                         ->with('success', 'Livestream scheduled successfully.');
    }

    public function edit(Livestream $livestream)
    {
        return view('admin.livestreams.edit', compact('livestream'));
    }

    public function update(Request $request, Livestream $livestream)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'stream_url'   => ['nullable', 'url'],
            'platform'     => ['required', 'in:youtube,vimeo,rtmp'],
            'scheduled_at' => ['nullable', 'date'],
            'status'       => ['required', 'in:scheduled,live,ended'],
        ]);

        if ($data['status'] === 'live' && !$livestream->started_at) {
            $data['started_at'] = now();
        }

        if ($data['status'] === 'ended' && !$livestream->ended_at) {
            $data['ended_at'] = now();
        }

        $livestream->update($data);
        ActivityLog::record('Updated Livestream', "Updated stream: {$livestream->title}", $livestream);

        return redirect()->route('admin.livestreams.index')
                         ->with('success', 'Livestream updated successfully.');
    }

    public function goLive(Livestream $livestream)
    {
        $livestream->update([
            'status'     => 'live',
            'started_at' => now(),
        ]);

        ActivityLog::record('Started Livestream', "Went live: {$livestream->title}", $livestream);

        return back()->with('success', 'Stream is now LIVE!');
    }

    /**
     * Preview the livestream as an admin.
     */
    public function show(Livestream $livestream)
    {
        return view('public.livestream.show', compact('livestream'));
    }

    public function endStream(Livestream $livestream)
    {
        $livestream->update([
            'status'   => 'ended',
            'ended_at' => now(),
        ]);

        ActivityLog::record('Ended Livestream', "Ended stream: {$livestream->title}", $livestream);

        return back()->with('success', 'Stream ended.');
    }

    public function destroy(Livestream $livestream)
    {
        ActivityLog::record('Deleted Livestream', "Deleted stream: {$livestream->title}");
        $livestream->delete();

        return redirect()->route('admin.livestreams.index')
                         ->with('success', 'Livestream deleted.');
    }

    public function replyComment(Request $request, Livestream $livestream, StreamComment $comment)
{
    $data = $request->validate([
        'body' => ['required', 'string', 'max:1000'],
    ]);

    $comment->replies()->create([
        'livestream_id' => $livestream->id,
        'body'          => $data['body'],
        'is_admin'      => true,
        'admin_id'      => auth()->id(),
        'name'          => auth()->user()->full_name ?? 'Prayer Team',
    ]);

    ActivityLog::record('Replied to Comment', "Replied on stream: {$livestream->title}", $livestream);

    return back()->with('success', 'Reply posted.');
}

}
