<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;

class EventRegistrationController extends Controller
{
    /**
     * Display a listing of the event registrations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch registrations sorted by newest first and paginate them
        $registrations = EventRegistration::latest()->paginate(20);

        return view('admin.event-registrations.index', compact('registrations'));
    }

    /**
     * Stream download all registrations as a CSV data payload file.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $fileName = 'event_registrations_' . now()->format('Y_m_d_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Added 'Prayer Request' column to the structural schema array
        $columns = ['Full Name', 'Email', 'Phone', 'Country', 'Prayer Request', 'Registered At'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Stream data chunks efficiently to support larger data tables without running out of memory
            EventRegistration::oldest()->chunk(200, function($registrations) use($file) {
                foreach ($registrations as $registration) {
                    fputcsv($file, [
                        $registration->full_name,
                        $registration->email,
                        $registration->phone ?? 'N/A',
                        $registration->country,
                        $registration->prayer_request ?? 'None Provided',
                        $registration->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
