@extends('layouts.admin')
@section('title', 'Create Event')
@section('page-title', 'Create New Event')

@section('content')
<div style="max-width:680px;">
    <a href="{{ route('admin.events.index') }}" style="font-size:13px; color:#c9a227; margin-bottom:20px; display:inline-block;">← Back to Events</a>

    <div class="admin-card" style="padding:28px;">
        <form method="POST" action="{{ route('admin.events.store') }}">
            @csrf
            <div style="display:grid; gap:20px;">

                <div>
                    <label>Event Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Community Outreach Saturday">
                    @error('title')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="What is this event about?">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label>Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Lagos Island or Online (Zoom)">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label>Start Date & Time *</label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label>End Date & Time *</label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required>
                        @error('end_date')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.1); border-radius:8px;">
                        <input type="checkbox" name="requires_rsvp" id="requires_rsvp" value="1" {{ old('requires_rsvp') ? 'checked' : '' }} style="width:16px;height:16px;">
                        <label for="requires_rsvp" style="margin:0; cursor:pointer; color:#e2e8f0;">Require RSVP</label>
                    </div>
                    <div>
                        <label>Max Attendees <span style="color:#4a5568;">(optional)</span></label>
                        <input type="number" name="max_attendees" value="{{ old('max_attendees') }}" min="1" placeholder="Unlimited">
                    </div>
                </div>

                <div style="display:flex; gap:12px; padding-top:8px; border-top:1px solid rgba(201,162,39,0.1);">
                    <button type="submit" class="btn-gold" style="padding:10px 28px;">Create Event</button>
                    <a href="{{ route('admin.events.index') }}" class="btn-secondary" style="padding:10px 20px; display:inline-block;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
