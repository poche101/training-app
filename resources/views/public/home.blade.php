@extends('layouts.app')

@section('title', 'OFCC — Online Outreach Platform')

@section('content')

@php
    // Defuse all missing controller variables at once by creating safe defaults
    $liveLivestream  = $liveLivestream ?? null;
    $upcomingStream  = $upcomingStream ?? null;
    $upcomingEvents  = $upcomingEvents ?? collect();
    $announcements   = $announcements ?? collect();
    $testimonies     = $testimonies ?? collect();
@endphp

{{-- Hero Section --}}
<section style="background: linear-gradient(180deg, rgba(5,13,26,0.85) 0%, rgba(10,22,40,0.85) 60%, rgba(13,30,58,0.9) 100%),
                url('https://picsum.photos/id/1015/2000/1200') center/cover no-repeat;
                min-height: 90vh; display:flex; align-items:center; position:relative; overflow:hidden;">

    <!-- Blue Overlay -->
    <div style="position:absolute; inset:0; background: linear-gradient(135deg, rgba(30,64,175,0.65), rgba(15,23,42,0.75));"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
        <div class="max-w-3xl">

            {{-- Live / Upcoming Badge --}}
            @if(isset($liveLivestream) && $liveLivestream)
                <a href="{{ route('stream.view', $liveLivestream->id) }}" class="inline-flex items-center gap-2 mb-6 px-4 py-2 rounded-full" style="background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3);">
                    <span class="live-badge">● LIVE</span>
                    <span class="text-sm text-red-300">{{ $liveLivestream->title }}</span>
                    <span class="text-red-400 text-xs">→ Watch Now</span>
                </a>
            @elseif(isset($upcomingStream) && $upcomingStream)
                <div class="inline-flex items-center gap-2 mb-6 px-4 py-2 rounded-full" style="background:rgba(201,162,39,0.1); border:1px solid rgba(201,162,39,0.25);">
                    <span class="text-gold text-sm">📅 Next Stream:</span>
                    <span class="text-sm text-gray-300">{{ $upcomingStream->scheduled_at->format('D, d M · H:i') }}</span>
                </div>
            @endif

            <h1 style="font-family:'Cinzel',serif; font-size:clamp(2.2rem,5vw,4rem); font-weight:700; line-height:1.15; color:white; margin-bottom:1.5rem;">
                Reaching the<br>
                <span style="background:linear-gradient(135deg,#c9a227,#f0c84a); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">World</span> for Christ
            </h1>

            <p style="font-size:1.125rem; color:#9bb0c9; line-height:1.7; margin-bottom:2.5rem; max-width:580px;">
                Welcome to our community. Watch live streams, access training resources, participate in events, and be part of a global movement transforming lives.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ $liveLivestream
                    ? route('stream.view', $liveLivestream)
                    : route('livestreams') }}"
                   class="btn-gold text-base">
                    📺 {{ $liveLivestream ? 'Watch Live Now' : 'Watch Live Stream' }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Upcoming Events --}}
@if(isset($upcomingEvents) && $upcomingEvents->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-1">Calendar</p>
            <h2 style="font-family:'Cinzel',serif; font-size:1.75rem; font-weight:700; color:white;">Upcoming Events</h2>
        </div>
        <a href="{{ route('events') }}" class="btn-outline text-sm">View All →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($upcomingEvents as $event)
        <div class="rounded-xl p-5 hover:border-gold transition-colors" style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">
            <div class="flex items-start gap-4">
                <div class="text-center rounded-lg p-3 flex-shrink-0 gold-gradient" style="min-width:56px;">
                    <p class="text-navy font-bold text-lg leading-none">{{ $event->start_date->format('d') }}</p>
                    <p class="text-navy text-xs font-semibold">{{ $event->start_date->format('M') }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-1">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-400 mb-2">{{ $event->start_date->format('H:i') }} · {{ $event->location ?? 'Online' }}</p>
                    @auth
                        <form method="POST" action="{{ route('member.events.rsvp', $event) }}">
                            @csrf
                            <button class="text-xs text-gold hover:underline">RSVP →</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="text-xs text-gold hover:underline">Register to RSVP →</a>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Announcements --}}
@if(isset($announcements) && $announcements->count())
<section style="background: rgba(5,13,26,0.8);" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-1">Updates</p>
                <h2 style="font-family:'Cinzel',serif; font-size:1.75rem; font-weight:700; color:white;">Announcements</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($announcements as $announcement)
            <div class="rounded-xl p-5" style="background:rgba(14,25,46,0.8); border:1px solid {{ $announcement->is_pinned ? 'rgba(201,162,39,0.3)' : 'rgba(201,162,39,0.1)' }};">
                @if($announcement->is_pinned)
                    <span class="text-xs text-gold font-semibold uppercase tracking-wider">📌 Pinned</span>
                @endif
                <span class="inline-block mt-2 mb-3 text-xs px-2 py-1 rounded-full font-medium
                    {{ $announcement->type === 'urgent' ? 'bg-red-900/50 text-red-300' : 'bg-blue-900/40 text-blue-300' }}">
                    {{ ucfirst($announcement->type) }}
                </span>
                <h3 class="font-semibold text-white mb-2">{{ $announcement->title }}</h3>
                <p class="text-sm text-gray-400 leading-relaxed">{{ Str::limit($announcement->content, 120) }}</p>
                <p class="text-xs text-gray-500 mt-3">{{ $announcement->published_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if($testimonies->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-2">Stories</p>
        <h2 style="font-family:'Cinzel',serif; font-size:1.75rem; font-weight:700; color:white;">Testimonies</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($testimonies as $testimony)
        <div class="rounded-xl p-6" style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">
            <p class="text-2xl mb-4 text-gold">"</p>
            <p class="text-sm text-gray-300 leading-relaxed mb-4">{{ Str::limit($testimony->content, 160) }}</p>
            <p class="font-semibold text-white text-sm">— {{ $testimony->author_name }}</p>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- CTA Section --}}
<section style="background: linear-gradient(135deg, rgba(201,162,39,0.08), rgba(201,162,39,0.03)); border-top: 1px solid rgba(201,162,39,0.1); border-bottom: 1px solid rgba(201,162,39,0.1);" class="py-16">
    <div class="max-w-2xl mx-auto text-center px-4">
        <h2 style="font-family:'Cinzel',serif; font-size:2rem; font-weight:700; color:white; margin-bottom:1rem;">
            Be Part of the Movement
        </h2>
        <p class="text-gray-400 mb-8 leading-relaxed">
            Join thousands of members across the globe. Access live streams, resources, and events. Your outreach journey starts here.
        </p>
    </div>
</section>

@endsection
