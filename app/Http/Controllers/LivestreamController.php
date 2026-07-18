<?php

namespace App\Http\Controllers;

use App\Models\Livestream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\StreamComment;


class LivestreamController extends Controller
{
    public function index()
    {
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

    /**
     * Called every few seconds by the frontend while a user is on the
     * stream page. Registers/refreshes their presence and returns the
     * current live count.
     */
    public function heartbeat(Request $request, Livestream $livestream)
    {
        // Anonymous visitors get a random id stored in their session,
        // so refreshing the page doesn't count as a new viewer.
        $viewerId = $request->session()->get('viewer_uuid')
            ?? tap(Str::uuid()->toString(), fn ($id) => $request->session()->put('viewer_uuid', $id));

        $key = "livestream:{$livestream->id}:viewer:{$viewerId}";

        // TTL a bit longer than the polling interval, so a couple of
        // missed pings (tab backgrounded, brief network blip) don't
        // instantly drop the count.
        Cache::put($key, true, now()->addSeconds(20));

        // Track this viewer's key in a per-stream registry so we can
        // enumerate active viewers on file/database cache drivers,
        // which don't support key-pattern scans the way Redis does.
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

    private function countViewers(int $livestreamId): int
    {
        $registryKey = "livestream:{$livestreamId}:viewer_registry";
        $registry = Cache::get($registryKey, []);

        // Prune anything whose heartbeat has expired.
        $active = array_filter($registry, fn ($key) => Cache::has($key));

        Cache::put($registryKey, $active, now()->addMinutes(2));

        return count($active);
    }

    public function comments(Livestream $livestream)
{
    return response()->json([
        'comments' => $livestream->topLevelComments()->get(),
    ]);
}

public function storeComment(Request $request, Livestream $livestream)
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'body' => ['required', 'string', 'max:1000'],
    ]);

    $comment = $livestream->comments()->create($data);

    return response()->json(['comment' => $comment], 201);
}
}
