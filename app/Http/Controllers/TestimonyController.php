<?php

// app/Http/Controllers/TestimonyController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestimonyController extends Controller
{
    public function submit(Request $request)
{
    $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    \App\Models\Testimony::create([
        'author_name' => $request->name,
        'content'     => $request->message,
        'is_approved' => false,
    ]);

    return back()->with('success', 'Thank you! Your testimony has been submitted.');
}
}
