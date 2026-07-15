@extends('layouts.app')

@section('title', 'OFCC — Online Outreach Platform')

@section('content')

@php
    $liveLivestream  = $liveLivestream ?? null;
    $upcomingStream  = $upcomingStream ?? null;
    $upcomingEvents  = $upcomingEvents ?? collect();
    $announcements   = $announcements ?? collect();
    $testimonies     = $testimonies ?? collect();
@endphp

{{-- Hero Section --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden"
         style="background: linear-gradient(180deg, rgba(5,13,26,0.85) 0%, rgba(10,22,40,0.85) 60%, rgba(13,30,58,0.9) 100%),
                url('https://picsum.photos/id/1015/2000/1200') center/cover no-repeat;">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-slate-900/75 to-slate-950/80"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 py-16 md:py-24 relative z-10 w-full">
        <div class="max-w-2xl lg:max-w-3xl">

            {{-- Live / Upcoming Badge --}}
            @if($liveLivestream)
                <a href="{{ route('stream.view', $liveLivestream->id) }}"
                   class="inline-flex items-center gap-2 mb-6 px-5 py-2.5 rounded-full bg-red-500/10 border border-red-500/30 hover:bg-red-500/15 transition-colors">
                    <span class="live-badge">● LIVE</span>
                    <span class="text-sm text-red-200 font-medium">{{ $liveLivestream->title }}</span>
                    <span class="text-red-400 text-xs font-medium">→ Watch Now</span>
                </a>
            @elseif($upcomingStream)
                <div class="inline-flex items-center gap-2 mb-6 px-5 py-2.5 rounded-full bg-gold/10 border border-gold/30">
                    <span class="text-gold text-sm">📅 Next Stream:</span>
                    <span class="text-sm text-gray-200">{{ $upcomingStream->scheduled_at->format('D, d M · H:i') }}</span>
                </div>
            @endif

            <h1 class="font-cinzel font-bold text-white leading-[1.1] tracking-tight mb-6"
                style="font-size: clamp(2.25rem, 8vw, 4rem);">
                Reaching the<br>
                <span class="bg-gradient-to-r from-gold to-yellow-300 bg-clip-text text-transparent">World</span> for Christ
            </h1>

            <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-8 max-w-lg">
                Welcome to our community. Watch live streams, access training resources, participate in events, and be part of a global movement transforming lives.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ $liveLivestream ? route('stream.view', $liveLivestream->id) : route('livestreams') }}"
                   class="btn-gold text-base sm:text-lg px-8 py-4 inline-flex justify-center items-center gap-2">
                    📺 {{ $liveLivestream ? 'Watch Live Now' : 'Watch Live Stream' }}
                </a>

                @auth
                    <a href="{{ route('member.dashboard') }}"
                       class="text-base sm:text-lg px-8 py-4 inline-flex justify-center items-center gap-2 rounded-lg font-semibold text-white transition-all hover:-translate-y-0.5"
                       style="background: rgba(201,162,39,0.08); border: 1px solid rgba(201,162,39,0.35);">
                        ⊞ Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-base sm:text-lg px-8 py-4 inline-flex justify-center items-center gap-2 rounded-lg font-semibold text-white transition-all hover:-translate-y-0.5"
                       style="background: rgba(201,162,39,0.08); border: 1px solid rgba(201,162,39,0.35);">
                        ⊞ Member Dashboard
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Optional subtle bottom fade -->
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#0a1628] to-transparent"></div>
</section>

{{-- Member Quick Access --}}
@auth
<section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 py-14 lg:py-16">
    <div class="mb-8">
        <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-1">Your Account</p>
        <h2 class="font-cinzel text-2xl md:text-3xl font-bold text-white">Quick Access</h2>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <a href="{{ route('member.dashboard') }}"
           class="group rounded-2xl p-5 flex flex-col items-center text-center gap-3 transition-all hover:-translate-y-1 hover:border-gold"
           style="background:rgba(14,25,46,0.85); border:1px solid rgba(201,162,39,0.12);">
            <span class="text-2xl">⊞</span>
            <span class="text-sm font-medium text-white group-hover:text-gold">Dashboard</span>
        </a>

        <a href="{{ route('member.resources') }}"
           class="group rounded-2xl p-5 flex flex-col items-center text-center gap-3 transition-all hover:-translate-y-1 hover:border-gold"
           style="background:rgba(14,25,46,0.85); border:1px solid rgba(201,162,39,0.12);">
            <span class="text-2xl">📚</span>
            <span class="text-sm font-medium text-white group-hover:text-gold">Resources</span>
        </a>

        <a href="{{ route('member.announcements') }}"
           class="group rounded-2xl p-5 flex flex-col items-center text-center gap-3 transition-all hover:-translate-y-1 hover:border-gold"
           style="background:rgba(14,25,46,0.85); border:1px solid rgba(201,162,39,0.12);">
            <span class="text-2xl">📢</span>
            <span class="text-sm font-medium text-white group-hover:text-gold">Announcements</span>
        </a>

        <a href="{{ route('member.offerings') }}"
           class="group rounded-2xl p-5 flex flex-col items-center text-center gap-3 transition-all hover:-translate-y-1 hover:border-gold"
           style="background:rgba(14,25,46,0.85); border:1px solid rgba(201,162,39,0.12);">
            <span class="text-2xl">💰</span>
            <span class="text-sm font-medium text-white group-hover:text-gold">Offerings</span>
        </a>

        <a href="{{ route('member.profile') }}"
           class="group rounded-2xl p-5 flex flex-col items-center text-center gap-3 transition-all hover:-translate-y-1 hover:border-gold"
           style="background:rgba(14,25,46,0.85); border:1px solid rgba(201,162,39,0.12);">
            <span class="text-2xl">👤</span>
            <span class="text-sm font-medium text-white group-hover:text-gold">Profile</span>
        </a>
    </div>
</section>
@endauth

{{-- Upcoming Events --}}
@if($upcomingEvents->count())
<section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 py-16 lg:py-20">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
            <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-1">Calendar</p>
            <h2 class="font-cinzel text-3xl md:text-4xl font-bold text-white">Upcoming Events</h2>
        </div>
        <a href="{{ route('events') }}" class="btn-outline text-sm whitespace-nowrap">View All →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($upcomingEvents as $event)
        <div class="group rounded-2xl p-6 transition-all hover:-translate-y-1 hover:border-gold"
             style="background:rgba(14,25,46,0.85); border:1px solid rgba(201,162,39,0.12);">
            <div class="flex gap-5">
                <div class="gold-gradient text-navy font-bold text-center rounded-xl px-4 py-3 flex-shrink-0 w-16">
                    <p class="text-2xl leading-none">{{ $event->start_date->format('d') }}</p>
                    <p class="text-xs uppercase tracking-wider">{{ $event->start_date->format('M') }}</p>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-white text-lg mb-1 line-clamp-2">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-400 mb-4">
                        {{ $event->start_date->format('H:i') }} • {{ $event->location ?? 'Online' }}
                    </p>
                    @auth
                        <form method="POST" action="{{ route('member.events.rsvp', $event) }}">
                            @csrf
                            <button class="text-gold hover:text-gold/80 text-sm font-medium">RSVP →</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="text-gold hover:text-gold/80 text-sm font-medium">Register to RSVP →</a>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Announcements --}}
@if($announcements->count())
<section class="py-16 lg:py-20" style="background: rgba(5,13,26,0.9);">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-1">Updates</p>
                <h2 class="font-cinzel text-3xl md:text-4xl font-bold text-white">Announcements</h2>
            </div>
            @auth
                <a href="{{ route('member.announcements') }}" class="btn-outline text-sm whitespace-nowrap">View All →</a>
            @endauth
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($announcements as $announcement)
            <div class="rounded-2xl p-6 transition-all hover:border-gold"
                 style="background:rgba(14,25,46,0.85); border:1px solid {{ $announcement->is_pinned ? 'rgba(201,162,39,0.4)' : 'rgba(201,162,39,0.12)' }};">

                @if($announcement->is_pinned)
                    <span class="inline-block text-xs font-bold uppercase tracking-widest text-gold mb-3">📌 PINNED</span>
                @endif

                <span class="inline-block text-xs px-3 py-1 rounded-full font-medium mb-4
                    {{ $announcement->type === 'urgent' ? 'bg-red-900/70 text-red-300' : 'bg-blue-900/50 text-blue-300' }}">
                    {{ ucfirst($announcement->type) }}
                </span>

                <h3 class="font-semibold text-white text-lg mb-3 line-clamp-2">{{ $announcement->title }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed line-clamp-4">{{ Str::limit($announcement->content, 160) }}</p>

                <p class="text-xs text-gray-500 mt-6">{{ $announcement->published_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if($testimonies->count())
<section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 py-16 lg:py-20">
    <div class="text-center mb-12">
        <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-2">Stories</p>
        <h2 class="font-cinzel text-3xl md:text-4xl font-bold text-white">Testimonies</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($testimonies as $testimony)
        <div class="rounded-2xl p-7 h-full flex flex-col"
             style="background:rgba(14,25,46,0.85); border:1px solid rgba(201,162,39,0.12);">
            <p class="text-4xl text-gold mb-4">“</p>
            <p class="text-gray-300 leading-relaxed flex-1">{{ Str::limit($testimony->content, 165) }}</p>
            <p class="font-semibold text-white mt-8">— {{ $testimony->author_name }}</p>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Final CTA --}}
<section class="py-20 border-t border-gold/10"
         style="background: linear-gradient(135deg, rgba(201,162,39,0.08), rgba(201,162,39,0.03));">
    <div class="max-w-2xl mx-auto text-center px-5">
        <h2 class="font-cinzel text-3xl md:text-4xl font-bold text-white mb-4">
            Be Part of the Movement
        </h2>
        <p class="text-gray-400 text-lg leading-relaxed max-w-md mx-auto">
            Join thousands of members across the globe. Access live streams, resources, and events.
        </p>
    </div>
</section>

@endsection
