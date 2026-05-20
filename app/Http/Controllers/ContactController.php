<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // 1. Validate inputs
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // 2. Save to database using model field names
        Testimony::create([
            'author_name' => $validated['name'],
            'content'     => $validated['message'],
            'is_approved' => false, // Default to false for manual review
        ]);

        // 3. Return with success message
        return back()->with('success', 'Thank you! Your testimony/request has been submitted for review.');
    }
}
