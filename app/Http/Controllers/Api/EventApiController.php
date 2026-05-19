<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'upcoming' => Event::upcoming()->get(),
            'past'     => Event::where('end_date','<',now())->latest()->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'requires_rsvp' => 'boolean',
        ]);

        $data['created_by'] = auth()->id();
        return response()->json(Event::create($data), 201);
    }
}
