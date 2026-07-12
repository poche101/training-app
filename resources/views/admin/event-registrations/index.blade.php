@extends('layouts.admin')
@section('title', 'Event Registrations')
@section('page-title', 'Event Registrations')

@section('content')
<div class="admin-card" style="padding:20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="font-size:14px; font-weight:600; color:white;">🎟️ All Registered Attendees (A Day Of Blessings)</h3>

        {{-- Excel Export Button --}}
        <a href="{{ route('admin.event-registrations.export') }}" class="btn-gold" style="font-size: 12px; display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;">
            <span>📥</span> Export Excel (CSV)
        </a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Country</th>
                <th>Prayer Request</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $registration)
            <tr>
                <td style="font-weight:500; color:white;">{{ $registration->full_name }}</td>
                <td style="color:#9ca3af;">{{ $registration->email }}</td>
                <td style="color:#9ca3af;">{{ $registration->phone ?? 'N/A' }}</td>
                <td>
                    <span style="font-size:11px; padding:2px 8px; border-radius:999px; background:rgba(96,165,250,0.1); color:#93c5fd;">
                        {{ $registration->country }}
                    </span>
                </td>
                <td>
                    @if($registration->prayer_request)
                        <div style="max-width: 250px; max-height: 60px; overflow-y: auto; font-size: 13px; color: #d1d5db; line-height: 1.4; padding-right: 4px;" class="admin-table-scrollbar">
                            {{ $registration->prayer_request }}
                        </div>
                    @else
                        <span style="color: #4b5563; font-style: italic; font-size: 13px;">None</span>
                    @endif
                </td>
                <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#6b7280; padding:16px;">No registrations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $registrations->links() }}
    </div>
</div>

<style>
    /* Minor utility style to keep internal prayer text scrollbars looking clean */
    .admin-table-scrollbar::-webkit-scrollbar { width: 4px; }
    .admin-table-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .admin-table-scrollbar::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.3); border-radius: 2px; }
    .admin-table-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(156, 163, 175, 0.5); }
</style>
@endsection
