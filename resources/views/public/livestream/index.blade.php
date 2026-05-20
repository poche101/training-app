@extends('layouts.app')

@section('title', 'Live Streams')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Announcements Section --}}
    @php
        $announcements = \App\Models\Announcement::latest()->take(3)->get();
    @endphp

    @if($announcements->isNotEmpty())
        <div class="mb-12 space-y-4">
            @foreach($announcements as $announcement)
                <div class="p-4 rounded-xl border border-gold/30 bg-gray-900/50 backdrop-blur-sm">
                    <h4 class="font-bold text-gold flex items-center gap-2">
                        @if($announcement->type === 'urgent') ⚡ @endif
                        {{ $announcement->title }}
                    </h4>
                    <p class="text-gray-300 text-sm mt-1">{{ $announcement->content }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="text-center mb-12">
        <p class="text-gold text-sm font-semibold uppercase tracking-widest mb-2">Watch</p>
        <h1 style="font-family:'Cinzel',serif; font-size:2rem; font-weight:700; color:white;">
            Live Streams
        </h1>
    </div>

    @if($livestreams->isEmpty())
        <div class="text-center py-20">
            <p class="text-5xl mb-4">📡</p>
            <h2 class="text-white text-xl font-semibold mb-2">No streams right now</h2>
            <p class="text-gray-400 mb-6">Check back soon — something is coming.</p>
            <a href="{{ route('home') }}" class="btn-gold">← Back to Home</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($livestreams as $stream)
            <a href="{{ route('stream.view', $stream) }}"
               class="rounded-xl overflow-hidden hover:border-gold transition-colors block"
               style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">

                {{-- Thumbnail --}}
                <div style="aspect-ratio:16/9; background:#0a1628; position:relative;">
                    @if($stream->thumbnail)
                        <img src="{{ Storage::url($stream->thumbnail) }}"
                             alt="{{ $stream->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">📺</div>
                    @endif

                    @if($stream->status === 'live')
                        <span class="absolute top-3 left-3 live-badge">● LIVE</span>
                    @else
                        <span class="absolute top-3 left-3 text-xs px-2 py-1 rounded-full font-semibold"
                              style="background:rgba(201,162,39,0.2); color:#c9a227;">
                            📅 Upcoming
                        </span>
                    @endif
                </div>

                <div class="p-5">
                    <h3 class="font-semibold text-white mb-1">{{ $stream->title }}</h3>
                    @if($stream->scheduled_at)
                        <p class="text-sm text-gray-400">
                            {{ $stream->scheduled_at->format('D, d M Y · H:i') }}
                        </p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    @endif

    {{-- Testimony & Prayer Request Section --}}
    <div class="mt-20 p-8 rounded-xl bg-gray-900 border border-gray-700 max-w-2xl mx-auto">
        <h2 class="text-2xl text-white font-bold mb-6 text-center">Share a Testimony or Prayer Request</h2>

        @if(session('success'))
            <div class="p-4 mb-6 text-center text-green-400 bg-green-900/20 border border-green-600/30 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-6 text-red-400 bg-red-900/20 border border-red-600/30 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('testimony.submit') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <input
                    type="text"
                    name="name"
                    placeholder="Your Name"
                    value="{{ old('name') }}"
                    class="w-full p-3 rounded bg-gray-800 text-white border border-gray-700 focus:border-gold outline-none"
                    required>
                <textarea
                    name="message"
                    rows="4"
                    placeholder="Your testimony or prayer request..."
                    class="w-full p-3 rounded bg-gray-800 text-white border border-gray-700 focus:border-gold outline-none"
                    required>{{ old('message') }}</textarea>
                <button type="submit" class="w-full py-3 bg-gold text-black font-bold rounded hover:bg-yellow-600 transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>

</section>
@endsection
