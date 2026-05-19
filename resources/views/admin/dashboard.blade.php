@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')

{{-- Live Alert --}}
@if($liveLivestream)
<div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); border-radius:12px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between;">
    <div style="display:flex; align-items:center; gap:10px;">
        <span class="badge-live">● LIVE</span>
        <span style="font-size:14px; color:#fca5a5;">{{ $liveLivestream->title }}</span>
    </div>
    <a href="{{ route('admin.livestreams.show', $liveLivestream) }}" style="font-size:12px; color:#c9a227;">Manage →</a>
</div>
@endif

{{-- Stats Grid --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:24px;">
    @php
    $statCards = [
        ['label'=>'Total Members', 'value'=>number_format($stats['total_users']), 'icon'=>'👥', 'color'=>'#c9a227'],
        ['label'=>'Total Streams', 'value'=>$stats['total_streams'], 'icon'=>'📺', 'color'=>'#60a5fa'],
        ['label'=>'Live Now', 'value'=>$stats['live_streams'], 'icon'=>'🔴', 'color'=>'#ef4444'],
        ['label'=>'Resources', 'value'=>$stats['total_resources'], 'icon'=>'📚', 'color'=>'#34d399'],
        ['label'=>'Total Events', 'value'=>$stats['total_events'], 'icon'=>'📅', 'color'=>'#a78bfa'],
        ['label'=>'Downloads', 'value'=>number_format($stats['total_downloads']), 'icon'=>'↓', 'color'=>'#fb923c'],
        ['label'=>'Check-ins', 'value'=>number_format($stats['total_attendance']), 'icon'=>'✓', 'color'=>'#2dd4bf'],
        ['label'=>'Upcoming', 'value'=>$stats['upcoming_events'], 'icon'=>'⏰', 'color'=>'#f472b6'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="stat-card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
            <span style="font-size:20px;">{{ $card['icon'] }}</span>
        </div>
        <p style="font-size:1.5rem; font-weight:700; color:{{ $card['color'] }};">{{ $card['value'] }}</p>
        <p style="font-size:11px; color:#6b7280; margin-top:2px;">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

    {{-- Recent Members --}}
    <div class="admin-card" style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:14px; font-weight:600; color:white;">Recent Members</h3>
            <a href="{{ route('admin.users.index') }}" style="font-size:11px; color:#c9a227;">View all →</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($recentUsers as $user)
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#c9a227,#f0c84a); display:flex; align-items:center; justify-content:center; color:#080f1f; font-weight:700; font-size:13px; flex-shrink:0;">
                    {{ strtoupper(substr($user->full_name, 0, 1)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:500; color:white; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->full_name }}</p>
                    <p style="font-size:11px; color:#6b7280;">{{ $user->created_at->diffForHumans() }}</p>
                </div>
                <span style="font-size:10px; padding:2px 8px; border-radius:999px; background:rgba(201,162,39,0.1); color:#c9a227;">{{ $user->role }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Upcoming Events --}}
    <div class="admin-card" style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:14px; font-weight:600; color:white;">Upcoming Events</h3>
            <a href="{{ route('admin.events.index') }}" style="font-size:11px; color:#c9a227;">Manage →</a>
        </div>
        @forelse($upcomingEvents as $event)
        <div style="display:flex; gap:10px; align-items:flex-start; margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid rgba(201,162,39,0.06);">
            <div style="text-align:center; background:linear-gradient(135deg,#c9a227,#f0c84a); border-radius:8px; padding:6px 10px; flex-shrink:0;">
                <p style="font-size:14px; font-weight:700; color:#080f1f; line-height:1;">{{ $event->start_date->format('d') }}</p>
                <p style="font-size:10px; color:#080f1f; font-weight:600;">{{ $event->start_date->format('M') }}</p>
            </div>
            <div>
                <p style="font-size:13px; font-weight:500; color:white;">{{ $event->title }}</p>
                <p style="font-size:11px; color:#6b7280;">{{ $event->location ?? 'Online' }}</p>
            </div>
        </div>
        @empty
        <p style="font-size:13px; color:#6b7280;">No upcoming events.</p>
        @endforelse
    </div>
</div>

{{-- Activity Log --}}
<div class="admin-card" style="padding:20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="font-size:14px; font-weight:600; color:white;">📋 Recent Activity</h3>
        <a href="{{ route('admin.activity-logs') }}" style="font-size:11px; color:#c9a227;">View all →</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Admin</th>
                <th>Action</th>
                <th>Description</th>
                <th>IP</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activityLogs as $log)
            <tr>
                <td>{{ $log->admin?->full_name ?? 'System' }}</td>
                <td><span style="color:#c9a227;">{{ $log->action }}</span></td>
                <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $log->description }}</td>
                <td style="font-family:monospace; font-size:12px;">{{ $log->ip_address }}</td>
                <td>{{ $log->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
