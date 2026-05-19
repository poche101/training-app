<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $attendance = $user->attendances()->with('event')->latest()->take(10)->get();
        return view('admin.users.show', compact('user', 'attendance'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => ['required', 'in:member,moderator,resource_manager,media_admin,outreach_admin,super_admin']]);

        // Only super admin can assign admin roles
        if (!auth()->user()->isSuperAdmin() && $request->role !== 'member') {
            abort(403);
        }

        $user->update(['role' => $request->role]);
        ActivityLog::record('Updated User Role', "Changed {$user->full_name} role to {$request->role}", $user);

        return back()->with('success', 'User role updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        ActivityLog::record('Deleted User', "Deleted user: {$user->full_name}");
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted.');
    }

    public function activityLogs()
    {
        $logs = ActivityLog::with('admin')->latest()->paginate(30);
        return view('admin.users.activity-logs', compact('logs'));
    }
}
