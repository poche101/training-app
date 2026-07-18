<?php

namespace App\Http\Controllers;

use App\Models\Livestream;
use App\Models\StreamComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LivestreamController extends Controller
{
    public function index()
    {
        // Fetch all livestreams ordered by their start/scheduled time
        $livestreams = Livestream::orderBy('started_at', 'desc')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(9);

        // Pass the exact variable name your Blade view is expecting
        return view('public.livestream.index', compact('livestreams'));
    }

    public function show(Livestream $livestream)
    {
        return view('public.livestream.show', compact('livestream'));
    }

    /**
     * Called every few seconds by the frontend while a user is on the
     * stream page. Registers/refreshes their presence and returns the
     * current live count.
     */
    public function heartbeat(Request $request, Livestream $livestream)
    {
        $viewerId = $request->session()->get('viewer_uuid')
            ?? tap(Str::uuid()->toString(), fn ($id) => $request->session()->put('viewer_uuid', $id));

        $key = "livestream:{$livestream->id}:viewer:{$viewerId}";

        Cache::put($key, true, now()->addSeconds(20));

        $registryKey = "livestream:{$livestream->id}:viewer_registry";
        $registry = Cache::get($registryKey, []);
        $registry[$viewerId] = $key;
        Cache::put($registryKey, $registry, now()->addMinutes(2));

        return response()->json([
            'count' => $this->countViewers($livestream->id),
        ]);
    }

    public function viewerCount(Livestream $livestream)
    {
        return response()->json([
            'count' => $this->countViewers($livestream->id),
        ]);
    }

    /**
     * Returns all top-level comments (with their admin replies nested)
     * for a stream, polled by the frontend.
     */
    public function comments(Livestream $livestream)
    {
        return response()->json([
            'comments' => $livestream->topLevelComments()->get(),
        ]);
    }

    /**
     * Stores a guest comment on a stream. No auth required — viewers
     * identify themselves by typing a name in the form.
     */
    public function storeComment(Request $request, Livestream $livestream)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $livestream->comments()->create($data);

        return response()->json(['comment' => $comment], 201);
    }

    private function countViewers(int $livestreamId): int
    {
        $registryKey = "livestream:{$livestreamId}:viewer_registry";
        $registry = Cache::get($registryKey, []);

        $active = array_filter($registry, fn ($key) => Cache::has($key));

        Cache::put($registryKey, $active, now()->addMinutes(2));

        return count($active);
    }
}
