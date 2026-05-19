@extends('layouts.admin')
@section('title', $event->title)
@section('page-title', 'Event Details')

@section('content')

<a href="{{ route('admin.events.index') }}" style="font-size:13px; color:#c9a227; margin-bottom:20px; display:inline-block;">← Back to Events</a>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

    {{-- Event Info --}}
    <div class="admin-card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:16px;">
            <h2 style="font-size:18px; font-weight:700; color:white;">{{ $event->title }}</h2>
            <a href="{{ route('admin.events.edit', $event) }}" class="btn-secondary" style="padding:6px 14px;">Edit</a>
        </div>
        <div style="display:grid; gap:10px;">
            <div style="display:flex; gap:8px;">
                <span style="color:#6b7280; font-size:13px; min-width:80px;">Location</span>
                <span style="color:#e2e8f0; font-size:13px;">{{ $event->location ?? 'Online' }}</span>
            </div>
            <div style="display:flex; gap:8px;">
                <span style="color:#6b7280; font-size:13px; min-width:80px;">Start</span>
                <span style="color:#e2e8f0; font-size:13px;">{{ $event->start_date->format('D, d M Y — H:i') }}</span>
            </div>
            <div style="display:flex; gap:8px;">
                <span style="color:#6b7280; font-size:13px; min-width:80px;">End</span>
                <span style="color:#e2e8f0; font-size:13px;">{{ $event->end_date->format('D, d M Y — H:i') }}</span>
            </div>
            @if($event->description)
            <div style="margin-top:8px; padding-top:12px; border-top:1px solid rgba(201,162,39,0.08);">
                <p style="color:#9ca3af; font-size:13px; line-height:1.6;">{{ $event->description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid; gap:12px;">
        <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
            <span style="font-size:28px;">📋</span>
            <div>
                <p style="font-size:2rem; font-weight:700; color:#c9a227; line-height:1;">{{ $rsvps->count() }}</p>
                <p style="font-size:12px; color:#6b7280;">Total RSVPs</p>
            </div>
        </div>
        <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
            <span style="font-size:28px;">✅</span>
            <div>
                <p style="font-size:2rem; font-weight:700; color:#34d399; line-height:1;">{{ $attendees->count() }}</p>
                <p style="font-size:12px; color:#6b7280;">Checked In</p>
            </div>
        </div>
        <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
            <span style="font-size:28px;">📊</span>
            <div>
                @php $rate = $rsvps->count() > 0 ? round(($attendees->count()/$rsvps->count())*100) : 0; @endphp
                <p style="font-size:2rem; font-weight:700; color:#60a5fa; line-height:1;">{{ $rate }}%</p>
                <p style="font-size:12px; color:#6b7280;">Attendance Rate</p>
            </div>
        </div>
    </div>
</div>

{{-- Attendance List --}}
<div class="admin-card" style="overflow:hidden;">
    <div style="padding:16px 20px; border-bottom:1px solid rgba(201,162,39,0.08);">
        <h3 style="font-size:14px; font-weight:600; color:white;">Check-in Log</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Member</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Checked In At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendees as $i => $att)
            <tr>
                <td style="color:#4a5568;">{{ $i + 1 }}</td>
                <td style="color:white; font-weight:500;">{{ $att->user?->full_name }}</td>
                <td>{{ $att->user?->email }}</td>
                <td>{{ $att->user?->phone ?? '—' }}</td>
                <td>{{ $att->checked_in_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:28px; color:#4a5568;">No check-ins recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
