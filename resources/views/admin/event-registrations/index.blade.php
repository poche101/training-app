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
                <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#6b7280; padding:16px;">No registrations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $registrations->links() }}
    </div>
</div>
@endsection
