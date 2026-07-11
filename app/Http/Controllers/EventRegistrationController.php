<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'country'   => 'required|string|max:100',
        ]);

        EventRegistration::create($validated);

        return redirect()->back()->with('success', 'Thank you! You have successfully registered for A Day Of Blessings.');
    }
}
