@extends('layouts.app')

@section('title', $livestream->title . ' — Live Outreach')

@section('content')
<div class="min-h-screen bg-[#050d1a] py-8 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb Navigation --}}
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('livestreams') }}" class="text-gold hover:underline text-sm flex items-center gap-2">
                <span>←</span> Back to All Streams
            </a>
            <span class="text-xs text-gray-500 uppercase tracking-widest font-mono">Maximized Screen Mode</span>
        </div>

        {{-- Widescreen Media Display Container --}}
        <div class="space-y-6 mb-8">

            {{-- THE VIDEO PLAYER SCREEN (Maximized to Full Content Width) --}}
            {{-- Full Width Video Player Area --}}
<div class="relative rounded-2xl overflow-hidden shadow-2xl bg-black border border-gray-800/80" style="padding-top: 56.25%;">
    @if($livestream->status === 'live' && $livestream->stream_url)
        @php
            // Extract YouTube ID from various URL formats
            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=))([^"&?\/ ]{11})/', $livestream->stream_url, $match);
            $youtubeId = $match[1] ?? null;
        @endphp

        @if($youtubeId)
            <iframe class="absolute inset-0 w-full h-full"
                    src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&rel=0"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
        @else
            {{-- Fallback if the URL doesn't contain a valid YouTube ID --}}
            <div class="absolute inset-0 flex items-center justify-center text-white">
                <p>Invalid Stream URL: {{ $livestream->stream_url }}</p>
            </div>
        @endif
    @else
        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-[#0a1628] to-[#0d1e3a]">
            <span class="text-6xl mb-4">⏳</span>
            <h3 class="text-2xl font-bold text-gray-200">Broadcast Offline</h3>
        </div>
    @endif
</div>

            {{-- Main Meta Header Panel --}}
            <div class="bg-[#0e192e] rounded-xl p-6 border border-gray-800">
                <div class="flex items-center gap-3 mb-3">
                    @if($livestream->status === 'live')
                        <span class="px-3 py-1 text-[10px] font-extrabold bg-red-600 text-white rounded-full tracking-wider animate-pulse">● LIVE BROADCAST</span>
                    @else
                        <span class="px-3 py-1 text-[10px] font-extrabold bg-gray-700 rounded-full text-gray-300 tracking-wider">OFFLINE</span>
                    @endif
                    <span class="text-[10px] text-gold uppercase tracking-wider font-semibold bg-gold/10 px-2.5 py-0.5 rounded border border-gold/20">{{ ucfirst($livestream->platform) }} Interface</span>
                </div>
                <h1 class="text-3xl font-bold mb-3 font-serif tracking-tight text-white">{{ $livestream->title }}</h1>
                <p class="text-gray-400 text-sm leading-relaxed max-w-5xl">{{ $livestream->description ?? 'No extra contextual descriptions logged for this curriculum track.' }}</p>
            </div>
        </div>

        {{-- Resource & Participation Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Column 1: Interactive Training Manual Assets --}}
            <div class="bg-[#0e192e] rounded-xl p-6 border border-gray-800 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xl">📚</span>
                        <h3 class="font-bold text-lg font-serif text-gray-200">Training Manual & Handouts</h3>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed mb-6">
                        Access the interactive structural study outlines, manual printouts, and digital handouts customized specifically for today's active curriculum profile tracking session.
                    </p>

                    {{-- File Attachment Quick Download Layout --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 bg-[#060f1e] rounded-lg border border-gray-800 text-xs">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <span class="text-red-400 text-base flex-shrink-0">📄</span>
                                <span class="truncate font-medium text-gray-300">Outreach_Training_Manual_V2.pdf</span>
                            </div>
                            <span class="text-[10px] text-gray-500 ml-2 font-mono flex-shrink-0">4.8 MB</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-800">
                    <a href="{{ route('member.resources') }}" class="w-full inline-block text-center text-xs py-2.5 px-4 rounded-lg border border-gold text-gold hover:bg-gold hover:text-navy font-semibold tracking-wide transition-all">
                        Browse Complete Document Library →
                    </a>
                </div>
            </div>

            {{-- Column 2: Offering and Contribution Details --}}
            <div class="bg-[#0e192e] rounded-xl p-6 border border-gray-800 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xl">🤝</span>
                        <h3 class="font-bold text-lg font-serif text-gray-200">Offerings & Ministry Seeds</h3>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed mb-4">
                        Support the continuous distribution of global outreach programs, training modules, and technological assets by contributing your local seeds, tithes, and special ministry project offerings.
                    </p>

                    {{-- Local Transaction Ledger Accounts Details Display --}}
                    <div class="bg-[#060f1e] rounded-lg border border-gray-800 p-4 space-y-2.5 text-xs">
                        <div class="flex justify-between items-center border-b border-gray-800/40 pb-2">
                            <span class="text-gray-400 font-medium">Bank Name:</span>
                            <span class="text-gray-200 font-semibold tracking-wide">Kingdom Grace Trust Bank</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-800/40 pb-2">
                            <span class="text-gray-400 font-medium">Account Name:</span>
                            <span class="text-gray-200 font-semibold">OFCC Ministries International</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-medium">Account Number (IBAN):</span>
                            <span class="text-gold font-mono font-bold tracking-wider">0123456789 / US-9876-54321</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-800">
                    <a href="{{ route('member.offerings') }}" class="w-full inline-block text-center text-xs py-2.5 px-4 bg-gradient-to-r from-gold to-[#f0c84a] text-navy rounded-lg font-bold tracking-wide hover:shadow-lg hover:shadow-gold/10 transition-all">
                        Access Secure Online Giving Portal 💳
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
