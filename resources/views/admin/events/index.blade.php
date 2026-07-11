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
                <th style="text-align:center;">RSVPs</th>
                <th style="text-align:center;">Attended</th>
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

                        <button
                            type="button"
                            class="btn-danger"
                            style="cursor:pointer;"
                            onclick="openAdminDeleteModal('{{ route('admin.events.destroy', $event) }}', '{{ addslashes($event->title) }}')"
                        >
                            Delete
                        </button>
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

<div
    id="adminDeleteModal"
    style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; padding: 16px; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px);"
>
    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 16px; max-w: 400px; w: 100%; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); font-family: system-ui, sans-serif;">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; height: 48px; width: 48px; rounded-radius: 50%; background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 24px; border-radius: 50%;">
                ⚠️
            </div>
            <h3 style="color: white; font-size: 18px; font-weight: 700; margin: 0 0 8px 0;">Confirm Event Deletion</h3>
            <p style="color: #94a3b8; font-size: 14px; margin: 0; line-height: 1.5;">
                Are you sure you want to permanently delete <span id="modalEventTitle" style="color: white; font-weight: 600;"></span>? This step cannot be reversed.
            </p>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button
                type="button"
                onclick="closeAdminDeleteModal()"
                style="padding: 8px 16px; font-size: 14px; font-weight: 500; color: #94a3b8; background: #334155; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s;"
                onmouseover="this.style.background='#475569'"
                onmouseout="this.style.background='#334155'"
            >
                Cancel
            </button>
            <form id="modalDeleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    style="padding: 8px 16px; font-size: 14px; font-weight: 500; color: white; background: #ef4444; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#dc2626'"
                    onmouseout="this.style.background='#ef4444'"
                >
                    Delete Now
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openAdminDeleteModal(actionUrl, eventTitle) {
        const modal = document.getElementById('adminDeleteModal');
        const form = document.getElementById('modalDeleteForm');
        const titleSpan = document.getElementById('modalEventTitle');

        form.action = actionUrl;
        titleSpan.textContent = `"${eventTitle}"`;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeAdminDeleteModal() {
        const modal = document.getElementById('adminDeleteModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Escape Key compatibility
    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAdminDeleteModal();
        }
    });
</script>

@endsection
