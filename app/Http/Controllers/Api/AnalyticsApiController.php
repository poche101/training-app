<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Livestream;
use App\Models\Resource;
use App\Models\User;

class AnalyticsApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_users'      => User::count(),
            'live_streams'     => Livestream::live()->count(),
            'total_downloads'  => Resource::sum('download_count'),
            'total_attendance' => Attendance::count(),
            'recent_members'   => User::latest()->take(5)->get(['id','full_name','email','role','created_at']),
        ]);
    }

    public function engagement()
    {
        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthly[] = [
                'month'     => $month->format('M Y'),
                'new_users' => User::whereYear('created_at',$month->year)->whereMonth('created_at',$month->month)->count(),
                'downloads' => Resource::whereYear('created_at',$month->year)->whereMonth('created_at',$month->month)->sum('download_count'),
            ];
        }

        return response()->json([
            'monthly'       => $monthly,
            'top_resources' => Resource::orderBy('download_count','desc')->take(5)->get(['id','title','download_count','file_type']),
        ]);
    }
}
