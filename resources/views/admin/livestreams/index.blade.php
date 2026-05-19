@extends('layouts.admin')
@section('title', 'Livestreams')
@section('page-title', 'Livestream Management')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <div></div>
    <a href="{{ route('admin.livestreams.create') }}" class="btn-gold">+ Schedule Stream</a>
</div>

<div class="admin-card" style="overflow:hidden;">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Platform</th>
                <th>Status</th>
                <th>Scheduled</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($livestreams as $stream)
            <tr>
                <td>
                    <p style="color:white; font-weight:500;">{{ $stream->title }}</p>
                    <p style="font-size:11px; color:#6b7280;">By {{ $stream->creator?->full_name }}</p>
                </td>
                <td>{{ ucfirst($stream->platform) }}</td>
                <td>
                    @if($stream->status === 'live')
                        <span class="badge-live">● LIVE</span>
                    @elseif($stream->status === 'scheduled')
                        <span style="font-size:11px; color:#fbbf24; background:rgba(251,191,36,0.1); padding:2px 8px; border-radius:999px;">Scheduled</span>
                    @else
                        <span style="font-size:11px; color:#6b7280; background:rgba(107,114,128,0.1); padding:2px 8px; border-radius:999px;">Ended</span>
                    @endif
                </td>
                <td>{{ $stream->scheduled_at?->format('d M Y, H:i') ?? '—' }}</td>
                <td>
                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                        @if($stream->status === 'scheduled')
                            <form method="POST" action="{{ route('admin.livestreams.go-live', $stream) }}">
                                @csrf
                                <button style="background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; padding:4px 12px; border-radius:6px; font-size:11px; cursor:pointer;">Go Live</button>
                            </form>
                        @elseif($stream->status === 'live')
                            <form method="POST" action="{{ route('admin.livestreams.end', $stream) }}">
                                @csrf
                                <button style="background:rgba(107,114,128,0.2); border:1px solid rgba(107,114,128,0.3); color:#9ca3af; padding:4px 12px; border-radius:6px; font-size:11px; cursor:pointer;">End Stream</button>
                            </form>
                        @endif
                        <a href="{{ route('admin.livestreams.edit', $stream) }}" class="btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('admin.livestreams.destroy', $stream) }}" onsubmit="return confirm('Delete this stream?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:40px; color:#4a5568;">No livestreams yet. <a href="{{ route('admin.livestreams.create') }}" style="color:#c9a227;">Schedule one →</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $livestreams->links() }}</div>

@endsection
