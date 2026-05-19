@extends('layouts.admin')
@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')

<div class="admin-card" style="overflow:hidden;">
    <table>
        <thead>
            <tr>
                <th>Admin</th>
                <th>Action</th>
                <th>Description</th>
                <th>IP Address</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>
                    <p style="color:white; font-size:13px; font-weight:500;">{{ $log->admin?->full_name ?? 'System' }}</p>
                    <p style="color:#6b7280; font-size:11px;">{{ $log->admin?->role }}</p>
                </td>
                <td>
                    <span style="color:#c9a227; font-size:12px; font-weight:600;">{{ $log->action }}</span>
                </td>
                <td style="max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:12px; color:#9ca3af;">
                    {{ $log->description ?: '—' }}
                </td>
                <td style="font-family:monospace; font-size:12px; color:#6b7280;">{{ $log->ip_address }}</td>
                <td>
                    <p style="font-size:12px;">{{ $log->created_at->format('d M Y, H:i') }}</p>
                    <p style="font-size:11px; color:#6b7280;">{{ $log->created_at->diffForHumans() }}</p>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:40px; color:#4a5568;">No activity logs yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $logs->links() }}</div>
@endsection
