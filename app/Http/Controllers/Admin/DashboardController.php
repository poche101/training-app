<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Livestream;
use App\Models\Resource;
use App\Models\User;
use App\Models\Testimony;
use App\Models\EventRegistration; // 1. IMPORT THE EVENT REGISTRATION MODEL

class DashboardController extends Controller
{
    // ─── REMOVED LEGACY __CONSTRUCT MIDDLEWARE METHODS ───────────────────
    // Middleware configurations belong inside routes/web.php in modern Laravel!

    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_streams'     => Livestream::count(),
            'live_streams'      => Livestream::live()->count(),
            'total_resources'   => Resource::count(),
            'total_events'      => Event::count(),
            'upcoming_events'   => Event::upcoming()->count(),
            'total_downloads'   => Resource::sum('download_count'),
            'total_attendance'  => Attendance::count(),
            'event_registrations' => EventRegistration::count(), // Added total count stat
        ];

        $recentUsers       = User::latest()->take(5)->get();
        $activityLogs      = ActivityLog::with('admin')->latest()->take(10)->get();
        $upcomingEvents    = Event::upcoming()->take(3)->get();
        $liveLivestream    = Livestream::live()->latest()->first();
        $recentTestimonies = Testimony::latest()->take(5)->get();

        // 2. FETCH THE NEW EVENT REGISTRATIONS (Latest 10 for dashboard overview)
        $eventRegistrations = EventRegistration::latest()->take(10)->get();

        // Chart data: registrations per month (last 6 months)
        $registrationData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $registrationData[] = [
                'label' => $month->format('M'),
                'count' => User::whereYear('created_at', $month->year)
                               ->whereMonth('created_at', $month->month)
                               ->count(),
            ];
        }

        // 3. VARIABLE IS PASSED INTO COMPACT()
        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'activityLogs',
            'upcomingEvents',
            'liveLivestream',
            'registrationData',
            'recentTestimonies',
            'eventRegistrations'
        ));
    }

    public function analytics()
    {
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyStats[] = [
                'label'     => $month->format('M Y'),
                'users'     => User::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
                'downloads' => Resource::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->sum('download_count'),
                'events'    => Event::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
            ];
        }

        $topResources = Resource::orderBy('download_count', 'desc')->take(10)->get();
        $eventAttendance = Attendance::selectRaw('event_id, count(*) as count')
                                     ->groupBy('event_id')
                                     ->with('event')
                                     ->get();

        return view('admin.analytics', compact('monthlyStats', 'topResources', 'eventAttendance'));
    }
}
