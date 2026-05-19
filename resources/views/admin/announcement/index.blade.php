@extends('layouts.admin')
@section('title', 'Announcements')
@section('page-title', 'Announcement Management')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
    <a href="{{ route('admin.announcements.create') }}" class="btn-gold">+ New Announcement</a>
</div>

<div class="admin-card" style="overflow:hidden;">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Pinned</th>
                <th>Published</th>
                <th>By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($announcements as $ann)
            <tr>
                <td>
                    <p style="color:white; font-weight:500;">{{ $ann->title }}</p>
                    <p style="font-size:11px; color:#6b7280; margin-top:2px;">{{ Str::limit($ann->content, 60) }}</p>
                </td>
                <td>
                    <span style="font-size:11px; padding:2px 8px; border-radius:999px; font-weight:600;
                        {{ $ann->type==='urgent' ? 'background:rgba(239,68,68,0.15); color:#fca5a5;' : ($ann->type==='event' ? 'background:rgba(96,165,250,0.15); color:#93c5fd;' : 'background:rgba(107,114,128,0.15); color:#9ca3af;') }}">
                        {{ ucfirst($ann->type) }}
                    </span>
                </td>
                <td>{{ $ann->is_pinned ? '📌 Yes' : '—' }}</td>
                <td>
                    @if($ann->published_at)
                        <p style="font-size:12px;">{{ $ann->published_at->format('d M Y') }}</p>
                        <p style="font-size:11px; color:#6b7280;">{{ $ann->published_at->diffForHumans() }}</p>
                    @else
                        <span style="color:#4a5568; font-size:12px;">Draft</span>
                    @endif
                </td>
                <td>{{ $ann->creator?->full_name }}</td>
                <td>
                    <div style="display:flex; gap:6px;">
                        <a href="{{ route('admin.announcements.edit', $ann) }}" class="btn-secondary" style="padding:4px 12px;">Edit</a>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}" onsubmit="return confirm('Delete this announcement?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:40px; color:#4a5568;">No announcements yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $announcements->links() }}</div>
@endsection
