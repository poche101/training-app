@extends('layouts.member')
@section('title', 'Events')
@section('page-title', 'Events & Outreach')

@section('content')

@if($upcoming->count())
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($upcoming as $event)
    <div class="card">
        <div class="flex items-start gap-4">
            <div class="text-center rounded-xl p-3 flex-shrink-0 gold-gradient" style="min-width:60px;">
                <p class="text-navy font-bold text-xl leading-none">{{ $event->start_date->format('d') }}</p>
                <p class="text-navy text-sm font-semibold">{{ $event->start_date->format('M') }}</p>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-white mb-1">{{ $event->title }}</h3>
                @if($event->description)
                    <p class="text-xs text-gray-400 mb-2">{{ Str::limit($event->description, 100) }}</p>
                @endif
                <div class="flex flex-wrap gap-2 text-xs text-gray-400 mb-3">
                    @if($event->location)
                        <span>📍 {{ $event->location }}</span>
                    @endif
                    <span>🕐 {{ $event->start_date->format('H:i') }} – {{ $event->end_date->format('H:i') }}</span>
                </div>
                <div class="flex gap-2 flex-wrap">
                    @if($myRsvps->contains($event->id))
                        <span class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:rgba(34,197,94,0.15); color:#86efac; border:1px solid rgba(34,197,94,0.2);">
                            ✓ RSVP'd
                        </span>
                    @elseif($event->requires_rsvp)
                        <form method="POST" action="{{ route('member.events.rsvp', $event) }}">
                            @csrf
                            <button class="btn-gold text-xs px-4 py-1.5">RSVP Now</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('member.events.checkin', $event) }}">
                        @csrf
                        <button class="btn-outline text-xs px-4 py-1.5">Check In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card text-center py-16">
    <p class="text-4xl mb-4">📅</p>
    <p class="text-gray-400">No upcoming events at the moment.</p>
    <p class="text-xs text-gray-500 mt-2">Check back soon for new outreach events.</p>
</div>
@endif

@endsection
