@extends('layouts.app')

@section('title', $livestream->title . ' — Live Outreach')

@section('content')
<div class="min-h-screen bg-[#050d1a] py-8 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb Navigation --}}
        <div class="mb-6">
            <a href="{{ route('livestreams') }}" class="text-gold hover:underline text-sm">← Back to All Streams</a>
        </div>

        {{-- Core View Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Column: Video Player & Meta Details (Spans 2 columns) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Dynamic Responsive Video Player Frame --}}
                <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-black border border-gray-800" style="padding-top: 56.25%;">
                    @if($livestream->status === 'live' && $livestream->stream_url)
                        {{-- Standard YouTube Embed Example --}}
                        @if(str_contains($livestream->stream_url, 'youtube.com') || str_contains($livestream->stream_url, 'youtu.be'))
                            @php
                                // Simple extraction of YouTube Video ID for clean rendering
                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $livestream->stream_url, $match);
                                $youtubeId = $match[1] ?? '';
                            @php
                            <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            {{-- Fallback generic player --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 p-4">
                                <p class="mb-4 text-center">External Live Broadcast Stream Channel Detected:</p>
                                <a href="{{ $livestream->stream_url }}" target="_blank" class="btn-gold">Launch External Broadcast Link ↗</a>
                            </div>
                        @endif
                    @else
                        {{-- Scheduled / Offline Placeholder Card --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-[#0a1628] to-[#0d1e3a]">
                            <span class="text-4xl mb-3">⏳</span>
                            <h3 class="text-xl font-bold mb-1">Broadcast Offline</h3>
                            <p class="text-sm text-gray-400 max-w-sm">
                                @if($livestream->status === 'scheduled' && $livestream->scheduled_at)
                                    This event is scheduled to go live on <br><strong>{{ $livestream->scheduled_at->format('F d, Y \a\t H:i') }}</strong>
                                @else
                                    This broadcast stream recording session has concluded.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Stream Header Information --}}
                <div class="bg-[#0e192e] rounded-xl p-6 border border-gray-800">
                    <div class="flex items-center gap-3 mb-3">
                        @if($livestream->status === 'live')
                            <span class="px-3 py-1 text-xs font-bold bg-red-600 rounded-full animate-pulse">● LIVE</span>
                        @else
                            <span class="px-3 py-1 text-xs font-bold bg-gray-700 rounded-full text-gray-300">OFFLINE</span>
                        @endif
                        <span class="text-xs text-gold uppercase tracking-wider font-semibold">{{ ucfirst($livestream->platform) }} Integration</span>
                    </div>
                    <h1 class="text-2xl font-bold mb-2 font-serif">{{ $livestream->title }}</h1>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $livestream->description ?? 'No extra description provided for this session.' }}</p>
                </div>
            </div>

            {{-- Right Column: Interactive Participation Sidebar --}}
            <div class="lg:col-span-1 flex flex-col space-y-6">

                {{-- Chat Box Panel Placeholders --}}
                <div class="bg-[#0e192e] rounded-xl border border-gray-800 flex flex-col h-[500px]">
                    <div class="p-4 border-b border-gray-800 bg-[#0a1628] rounded-t-xl">
                        <h3 class="font-bold text-sm tracking-wide text-gray-200">💬 Live Participation Chat</h3>
                    </div>

                    {{-- Scrollable Message History Window --}}
                    <div class="flex-1 p-4 overflow-y-auto space-y-4 text-xs text-gray-300">
                        <div class="bg-gray-900/40 p-2 rounded">
                            <span class="font-bold text-gold">Moderator Node:</span> Welcome to the Online Outreach platform! Feel free to ask questions here during the broadcast event.
                        </div>
                        {{-- Chat items loop will render here via Alpine/Livewire --}}
                    </div>

                    {{-- Simple Text Input Action Footer --}}
                    <div class="p-4 border-t border-gray-800 bg-[#0a1628] rounded-b-xl">
                        <form onsubmit="event.preventDefault(); alert('Chat systems integration hook requested! Set up real-time WebSockets next.');">
                            <div class="flex gap-2">
                                <input type="text" placeholder="Type a message..." class="w-full bg-[#050d1a] border border-gray-700 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-gold">
                                <button type="submit" class="px-3 py-2 bg-gold text-navy rounded-lg text-xs font-bold hover:opacity-90">Send</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Interactive Resource / Material Download Panel Widget --}}
                <div class="bg-[#0e192e] rounded-xl p-5 border border-gray-800">
                    <h4 class="font-bold text-sm text-gray-200 mb-3 font-serif">📚 Accompanying Resources</h4>
                    <p class="text-xs text-gray-400 mb-4">Download reading notes, outline tracking guides, and reference attachments provided for today's media broadcast session.</p>
                    <a href="{{ route('member.resources') }}" class="w-full inline-block text-center text-xs py-2 px-4 rounded border border-gold text-gold hover:bg-gold hover:text-navy transition-colors font-medium">
                        Open Resource Library →
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
