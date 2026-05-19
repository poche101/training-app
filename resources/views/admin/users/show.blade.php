@extends('layouts.admin')
@section('title', $user->full_name)
@section('page-title', 'Member Profile')

@section('content')

<a href="{{ route('admin.users.index') }}" style="font-size:13px; color:#c9a227; margin-bottom:20px; display:inline-block;">← Back to Users</a>

<div style="display:grid; grid-template-columns:320px 1fr; gap:20px; align-items:start;">

    {{-- Profile Card --}}
    <div>
        <div class="admin-card" style="padding:24px; text-align:center; margin-bottom:16px;">
            <div style="width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg,#c9a227,#f0c84a); display:flex; align-items:center; justify-content:center; color:#080f1f; font-weight:700; font-size:26px; margin:0 auto 12px;">
                {{ strtoupper(substr($user->full_name, 0, 1)) }}
            </div>
            <h2 style="font-size:16px; font-weight:700; color:white; margin-bottom:4px;">{{ $user->full_name }}</h2>
            <p style="font-size:12px; color:#6b7280; margin-bottom:12px;">{{ $user->email }}</p>
            @php
                $roleColors = ['super_admin'=>'color:#ef4444;','outreach_admin'=>'color:#c9a227;','media_admin'=>'color:#60a5fa;','resource_manager'=>'color:#a78bfa;','moderator'=>'color:#34d399;','member'=>'color:#9ca3af;'];
            @endphp
            <span style="font-size:12px; font-weight:700; {{ $roleColors[$user->role] ?? '' }}">
                {{ ucfirst(str_replace('_',' ',$user->role)) }}
            </span>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:16px; padding-top:16px; border-top:1px solid rgba(201,162,39,0.1);">
                <div style="background:rgba(5,13,26,0.6); border-radius:8px; padding:10px;">
                    <p style="font-size:18px; font-weight:700; color:#c9a227;">{{ $attendance->count() }}</p>
                    <p style="font-size:10px; color:#6b7280;">Attended</p>
                </div>
                <div style="background:rgba(5,13,26,0.6); border-radius:8px; padding:10px;">
                    <p style="font-size:18px; font-weight:700; color:#34d399;">{{ $user->eventRsvps()->count() }}</p>
                    <p style="font-size:10px; color:#6b7280;">RSVPs</p>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="admin-card" style="padding:20px; margin-bottom:16px;">
            <h3 style="font-size:13px; font-weight:600; color:white; margin-bottom:14px;">Contact Info</h3>
            <div style="display:grid; gap:10px;">
                <div>
                    <p style="font-size:10px; color:#6b7280; margin-bottom:2px;">Email</p>
                    <p style="font-size:13px; color:#e2e8f0;">{{ $user->email }}</p>
                </div>
                <div>
                    <p style="font-size:10px; color:#6b7280; margin-bottom:2px;">Phone</p>
                    <p style="font-size:13px; color:#e2e8f0;">{{ $user->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p style="font-size:10px; color:#6b7280; margin-bottom:2px;">Joined</p>
                    <p style="font-size:13px; color:#e2e8f0;">{{ $user->created_at->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:10px; color:#6b7280; margin-bottom:2px;">Email Verified</p>
                    <p style="font-size:13px; color:{{ $user->email_verified_at ? '#34d399' : '#fbbf24' }};">
                        {{ $user->email_verified_at ? '✓ ' . $user->email_verified_at->format('d M Y') : '⚠ Not verified' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Role Management --}}
        @if(auth()->user()->isSuperAdmin() && $user->id !== auth()->id())
        <div class="admin-card" style="padding:20px;">
            <h3 style="font-size:13px; font-weight:600; color:white; margin-bottom:14px;">Change Role</h3>
            <form method="POST" action="{{ route('admin.users.role', $user) }}">
                @csrf @method('PATCH')
                <select name="role" style="margin-bottom:10px;">
                    @foreach(['member','moderator','resource_manager','media_admin','outreach_admin','super_admin'] as $role)
                    <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_',' ',$role)) }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-gold" style="width:100%; padding:8px; text-align:center;">Update Role</button>
            </form>
        </div>
        @endif
    </div>

    {{-- Attendance History --}}
    <div class="admin-card" style="overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid rgba(201,162,39,0.08);">
            <h3 style="font-size:14px; font-weight:600; color:white;">Attendance History</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Location</th>
                    <th>Event Date</th>
                    <th>Checked In</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $att)
                <tr>
                    <td style="color:white; font-weight:500;">{{ $att->event?->title }}</td>
                    <td>{{ $att->event?->location ?? 'Online' }}</td>
                    <td>{{ $att->event?->start_date->format('d M Y') }}</td>
                    <td>{{ $att->checked_in_at->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:28px; color:#4a5568;">No attendance records.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
