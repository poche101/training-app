@extends('layouts.admin')
@section('title', 'Schedule Livestream')
@section('page-title', 'Schedule New Livestream')

@section('content')
<div style="max-width:680px;">
    <a href="{{ route('admin.livestreams.index') }}" style="font-size:13px; color:#c9a227; display:inline-flex; align-items:center; gap:6px; margin-bottom:20px;">← Back to Livestreams</a>

    <div class="admin-card" style="padding:28px;">
        <form method="POST" action="{{ route('admin.livestreams.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="display:grid; gap:20px;">

                <div>
                    <label>Stream Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Sunday Service — 25 May 2025">
                    @error('title')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Brief description of the stream...">{{ old('description') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label>Platform *</label>
                        <select name="platform">
                            <option value="youtube" {{ old('platform')=='youtube'?'selected':'' }}>YouTube Live</option>
                            <option value="vimeo"   {{ old('platform')=='vimeo'  ?'selected':'' }}>Vimeo</option>
                            <option value="rtmp"    {{ old('platform')=='rtmp'   ?'selected':'' }}>RTMP / Custom</option>
                        </select>
                    </div>
                    <div>
                        <label>Scheduled Date & Time</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}">
                    </div>
                </div>

                <div>
                    <label>Stream URL</label>
                    <input type="url" name="stream_url" value="{{ old('stream_url') }}" placeholder="https://youtube.com/watch?v=...">
                    @error('stream_url')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label>Thumbnail Image <span style="color:#4a5568;">(optional, max 2MB)</span></label>
                    <input type="file" name="thumbnail" accept="image/*" style="padding:6px 12px;">
                </div>

                <div style="display:flex; gap:12px; padding-top:8px; border-top:1px solid rgba(201,162,39,0.1);">
                    <button type="submit" class="btn-gold" style="padding:10px 28px;">Schedule Livestream</button>
                    <a href="{{ route('admin.livestreams.index') }}" class="btn-secondary" style="padding:10px 20px; display:inline-block;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
