@extends('layouts.member')
@section('title', 'Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="rounded-2xl p-6 mb-6 relative overflow-hidden" style="background:linear-gradient(135deg,#1a2d4e,#0d1e3a); border:1px solid rgba(201,162,39,0.2);">
    <div style="position:absolute; top:-40px; right:-40px; width:200px; height:200px; background:radial-gradient(circle,rgba(201,162,39,0.1),transparent 70%);"></div>
    <div class="relative z-10">
        <p class="text-gold text-sm font-semibold mb-1">Welcome back 👋</p>
        <h2 class="text-2xl font-bold text-white mb-2">{{ $user->full_name }}</h2>
        <p class="text-gray-400 text-sm">Continue your outreach journey today.</p>
    </div>
    @if($liveLivestream)
    <a href="{{ route('stream.show', $liveLivestream) }}" class="mt-4 inline-flex items-center gap-2 btn-gold">
        <span class="live-badge">● LIVE</span> Join Stream Now
    </a>
    @endif
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card text-center">
        <p class="text-2xl font-bold text-gold">{{ $upcomingEvents->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Upcoming Events</p>
    </div>
    <div class="card text-center">
        <p class="text-2xl font-bold text-gold">{{ $recentResources->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">New Resources</p>
    </div>
    <div class="card text-center">
        <p class="text-2xl font-bold text-gold">{{ $announcements->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Announcements</p>
    </div>
    <div class="card text-center">
        <p class="text-2xl font-bold text-gold">{{ auth()->user()->attendances()->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Events Attended</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Announcements --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-white">📢 Recent Announcements</h3>
            <a href="{{ route('member.announcements') }}" class="text-xs text-gold hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($announcements as $ann)
            <div class="p-3 rounded-lg" style="background:rgba(5,13,26,0.6); border:1px solid rgba(201,162,39,0.07);">
                @if($ann->is_pinned)
                    <span class="text-xs text-gold">📌 </span>
                @endif
                <p class="text-sm font-medium text-white">{{ $ann->title }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ Str::limit($ann->content, 80) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $ann->published_at->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-500">No announcements yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Upcoming Events --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-white">📅 Upcoming Events</h3>
            <a href="{{ route('member.events') }}" class="text-xs text-gold hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($upcomingEvents as $event)
            <div class="flex items-start gap-3 p-3 rounded-lg" style="background:rgba(5,13,26,0.6); border:1px solid rgba(201,162,39,0.07);">
                <div class="text-center rounded-lg p-2 flex-shrink-0 gold-gradient" style="min-width:44px;">
                    <p class="text-navy font-bold text-sm leading-none">{{ $event->start_date->format('d') }}</p>
                    <p class="text-navy text-xs">{{ $event->start_date->format('M') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ $event->title }}</p>
                    <p class="text-xs text-gray-400">{{ $event->location ?? 'Online' }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500">No upcoming events.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Resources --}}
    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-white">📚 Recent Resources</h3>
            <a href="{{ route('member.resources') }}" class="text-xs text-gold hover:underline">Browse all →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @forelse($recentResources as $res)
            <div class="flex items-center gap-3 p-3 rounded-lg" style="background:rgba(5,13,26,0.6); border:1px solid rgba(201,162,39,0.07);">
                <span class="text-2xl">{{ $res->file_icon }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $res->title }}</p>
                    <p class="text-xs text-gray-400">{{ $res->file_size }} · {{ strtoupper($res->file_type) }}</p>
                </div>
                <a href="{{ route('member.resources.download', $res) }}" class="text-xs text-gold hover:underline flex-shrink-0">↓ Download</a>
            </div>
            @empty
            <p class="text-sm text-gray-500">No resources available yet.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
