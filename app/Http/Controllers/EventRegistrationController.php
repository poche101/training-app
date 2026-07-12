<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRegistration;

class EventRegistrationController extends Controller
{
    /**
     * Handle incoming public event registration submissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validate the form submission including the new prayer request field
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:50',
            'country'        => 'required|string|max:100',
            'prayer_request' => 'nullable|string',
        ]);

        // 2. Save the entry to the event_registrations database table
        EventRegistration::create($validated);

        // 3. Redirect back to the page with a clean success flash status
        return redirect()->back()->with('success', 'Thank you! Your event registration and prayer request have been received successfully.');
    }
}
