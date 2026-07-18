@extends('layouts.admin')
@section('title', 'Edit Livestream')
@section('page-title', 'Edit Livestream')

@section('content')
<div style="max-width:680px;">
    <a href="{{ route('admin.livestreams.index') }}" style="font-size:13px; color:#c9a227; display:inline-flex; align-items:center; gap:6px; margin-bottom:20px;">← Back to Livestreams</a>

    {{-- Quick Controls --}}
    @if($livestream->status !== 'ended')
    <div class="admin-card" style="padding:16px 20px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <p style="font-size:13px; color:white; font-weight:600;">Stream Controls</p>
            <p style="font-size:11px; color:#6b7280;">Current status:
                <span style="color:{{ $livestream->status==='live'?'#ef4444':'#fbbf24' }}; font-weight:600;">{{ strtoupper($livestream->status) }}</span>
            </p>
        </div>
        <div style="display:flex; gap:8px;">
            @if($livestream->status === 'scheduled')
                <form method="POST" action="{{ route('admin.livestreams.go-live', $livestream) }}">
                    @csrf
                    <button style="background:#ef4444; color:white; border:none; padding:8px 18px; border-radius:8px; font-weight:700; font-size:13px; cursor:pointer;">● Go Live Now</button>
                </form>
            @elseif($livestream->status === 'live')
                <form method="POST" action="{{ route('admin.livestreams.end', $livestream) }}">
                    @csrf
                    <button style="background:rgba(107,114,128,0.3); color:#d1d5db; border:1px solid rgba(107,114,128,0.4); padding:8px 18px; border-radius:8px; font-size:13px; cursor:pointer;">End Stream</button>
                </form>
            @endif
        </div>
    </div>
    @endif

    {{-- Comments & Prayer Team Replies --}}
    <div class="admin-card" style="padding:20px; margin-bottom:16px;">
        <p style="font-size:13px; color:white; font-weight:600; margin-bottom:12px;">
            Comments
            <span style="color:#6b7280; font-weight:400;">({{ $livestream->topLevelComments()->count() }})</span>
        </p>

        @forelse($livestream->topLevelComments()->get() as $comment)
            <div style="padding:12px 0; border-bottom:1px solid rgba(255,255,255,0.06);">
                <p style="font-size:12px; color:#c9a227; font-weight:600;">{{ $comment->name ?? 'Guest' }}</p>
                <p style="font-size:13px; color:#d1d5db; margin-top:2px;">{{ $comment->body }}</p>

                @foreach($comment->replies as $reply)
                    <div style="margin-left:16px; margin-top:8px; padding:8px 12px; background:rgba(212,175,55,0.08); border-left:2px solid #c9a227; border-radius:6px;">
                        <span style="color:#fbbf24; font-size:11px; font-weight:600;">🙏 {{ $reply->name }}</span>
                        <p style="color:#e5e7eb; font-size:12px; margin-top:2px;">{{ $reply->body }}</p>
                    </div>
                @endforeach

                @if($comment->replies->isEmpty())
                    <form method="POST" action="{{ route('admin.livestreams.comments.reply', [$livestream, $comment]) }}" style="margin-top:8px; display:flex; gap:8px;">
                        @csrf
                        <input type="text" name="body" placeholder="Reply as Prayer Team..." required
                               style="flex:1; background:#0f172a; border:1px solid #334155; border-radius:8px; padding:6px 10px; font-size:12px; color:white;">
                        <button style="background:#c9a227; color:#0e192e; border:none; padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;">Reply</button>
                    </form>
                @endif
            </div>
        @empty
            <p style="font-size:12px; color:#6b7280;">No comments yet.</p>
        @endforelse
    </div>

    <div class="admin-card" style="padding:28px;">
        <form method="POST" action="{{ route('admin.livestreams.update', $livestream) }}">
            @csrf @method('PUT')
            <div style="display:grid; gap:20px;">

                <div>
                    <label>Stream Title *</label>
                    <input type="text" name="title" value="{{ old('title', $livestream->title) }}" required>
                    @error('title')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" rows="3">{{ old('description', $livestream->description) }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                    <div>
                        <label>Platform *</label>
                        <select name="platform">
                            <option value="youtube" {{ $livestream->platform=='youtube'?'selected':'' }}>YouTube</option>
                            <option value="vimeo"   {{ $livestream->platform=='vimeo'  ?'selected':'' }}>Vimeo</option>
                            <option value="rtmp"    {{ $livestream->platform=='rtmp'   ?'selected':'' }}>RTMP</option>
                        </select>
                    </div>
                    <div>
                        <label>Status *</label>
                        <select name="status">
                            <option value="scheduled" {{ $livestream->status=='scheduled'?'selected':'' }}>Scheduled</option>
                            <option value="live"      {{ $livestream->status=='live'     ?'selected':'' }}>Live</option>
                            <option value="ended"     {{ $livestream->status=='ended'    ?'selected':'' }}>Ended</option>
                        </select>
                    </div>
                    <div>
                        <label>Scheduled At</label>
                        <input type="datetime-local" name="scheduled_at"
                            value="{{ old('scheduled_at', $livestream->scheduled_at?->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div>
                    <label>Stream URL</label>
                    <input type="url" name="stream_url" value="{{ old('stream_url', $livestream->stream_url) }}" placeholder="https://youtube.com/watch?v=...">
                </div>

                <div style="display:flex; gap:12px; padding-top:8px; border-top:1px solid rgba(201,162,39,0.1);">
                    <button type="submit" class="btn-gold" style="padding:10px 28px;">Update Livestream</button>
                    <a href="{{ route('admin.livestreams.index') }}" class="btn-secondary" style="padding:10px 20px; display:inline-block;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
