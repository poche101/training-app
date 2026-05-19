@extends('layouts.admin')
@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')
<div style="max-width:680px;">
    <a href="{{ route('admin.announcements.index') }}" style="font-size:13px; color:#c9a227; margin-bottom:20px; display:inline-block;">← Back to Announcements</a>

    <div class="admin-card" style="padding:28px;">
        <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
            @csrf @method('PUT')
            <div style="display:grid; gap:20px;">

                <div>
                    <label>Announcement Title *</label>
                    <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required>
                    @error('title')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label>Content *</label>
                    <textarea name="content" rows="6" required>{{ old('content', $announcement->content) }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label>Type *</label>
                        <select name="type">
                            <option value="general" {{ $announcement->type=='general'?'selected':'' }}>General</option>
                            <option value="urgent"  {{ $announcement->type=='urgent' ?'selected':'' }}>Urgent</option>
                            <option value="event"   {{ $announcement->type=='event'  ?'selected':'' }}>Event</option>
                        </select>
                    </div>
                    <div>
                        <label>Publish Date</label>
                        <input type="datetime-local" name="published_at"
                            value="{{ old('published_at', $announcement->published_at?->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.1); border-radius:8px;">
                    <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ $announcement->is_pinned ? 'checked' : '' }} style="width:16px;height:16px;">
                    <label for="is_pinned" style="margin:0; cursor:pointer; color:#e2e8f0;">📌 Pin this Announcement</label>
                </div>

                <div style="display:flex; gap:12px; padding-top:8px; border-top:1px solid rgba(201,162,39,0.1);">
                    <button type="submit" class="btn-gold" style="padding:10px 28px;">Save Changes</button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn-secondary" style="padding:10px 20px; display:inline-block;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
