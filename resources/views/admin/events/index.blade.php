@extends('layouts.admin')
@section('title', 'Events')
@section('page-title', 'Event Management')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
    <a href="{{ route('admin.events.create') }}" class="btn-gold">+ Create Event</a>
</div>

<div class="admin-card" style="overflow:hidden;">
    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Location</th>
                <th>Date & Time</th>
                <th>RSVPs</th>
                <th>Attended</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td>
                    <p style="color:white; font-weight:500;">{{ $event->title }}</p>
                    <p style="font-size:11px; color:#6b7280;">By {{ $event->creator?->full_name }}</p>
                </td>
                <td>{{ $event->location ?? 'Online' }}</td>
                <td>
                    <p style="font-size:13px;">{{ $event->start_date->format('d M Y') }}</p>
                    <p style="font-size:11px; color:#6b7280;">{{ $event->start_date->format('H:i') }} – {{ $event->end_date->format('H:i') }}</p>
                </td>
                <td style="text-align:center;">{{ $event->rsvps_count }}</td>
                <td style="text-align:center;">{{ $event->attendances_count }}</td>
                <td>
                    @if($event->start_date->isFuture())
                        <span style="font-size:11px; color:#34d399; background:rgba(52,211,153,0.1); padding:2px 8px; border-radius:999px;">Upcoming</span>
                    @elseif($event->end_date->isFuture())
                        <span style="font-size:11px; color:#c9a227; background:rgba(201,162,39,0.1); padding:2px 8px; border-radius:999px;">Ongoing</span>
                    @else
                        <span style="font-size:11px; color:#6b7280; background:rgba(107,114,128,0.1); padding:2px 8px; border-radius:999px;">Ended</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex; gap:6px;">
                        <a href="{{ route('admin.events.show', $event) }}" class="btn-secondary" style="padding:4px 12px;">View</a>
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn-secondary" style="padding:4px 12px;">Edit</a>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:40px; color:#4a5568;">No events yet. <a href="{{ route('admin.events.create') }}" style="color:#c9a227;">Create one →</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $events->links() }}</div>
@endsection
