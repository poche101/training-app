<?php

// app/Http/Controllers/Admin/TestimonyController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimony;

class TestimonyController extends Controller
{
    public function index()
    {
        $testimonies = Testimony::latest()->paginate(15);
        return view('admin.testimonies.index', compact('testimonies'));
    }

    public function approve(Testimony $testimony)
    {
        $testimony->update(['is_approved' => true]);
        return back()->with('success', 'Testimony approved.');
    }

    public function destroy(Testimony $testimony)
    {
        $testimony->delete();
        return back()->with('success', 'Testimony deleted.');
    }
}
