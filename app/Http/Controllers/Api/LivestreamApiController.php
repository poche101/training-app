<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livestream;
use Illuminate\Http\Request;

class LivestreamApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'live'     => Livestream::live()->latest()->first(),
            'upcoming' => Livestream::upcoming()->get(),
            'past'     => Livestream::where('status','ended')->latest()->paginate(10),
        ]);
    }

    public function show($id)
    {
        return response()->json(Livestream::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'stream_url'   => 'nullable|url',
            'platform'     => 'required|in:youtube,vimeo,rtmp',
            'scheduled_at' => 'nullable|date',
        ]);

        $data['status']     = 'scheduled';
        $data['created_by'] = auth()->id();

        return response()->json(Livestream::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $stream = Livestream::findOrFail($id);
        $stream->update($request->only(['title','description','stream_url','platform','status','scheduled_at']));
        return response()->json($stream);
    }
}
