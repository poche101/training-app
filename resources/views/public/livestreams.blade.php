@extends('layouts.app')
@section('title', 'Live Streams — OFCC')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Live Now --}}
    @if($live)
    <div class="rounded-2xl overflow-hidden mb-12" style="border:2px solid rgba(239,68,68,0.5); box-shadow:0 0 40px rgba(239,68,68,0.1);">
        <div style="background:rgba(239,68,68,0.1); padding:14px 20px; display:flex; align-items:center; justify-content:space-between;">
            <div class="flex items-center gap-3">
                <span class="live-badge">● LIVE NOW</span>
                <span class="text-white font-semibold">{{ $live->title }}</span>
            </div>
            <a href="{{ route('stream.show', $live) }}" class="btn-gold text-sm">Watch Live →</a>
        </div>
        <div style="aspect-ratio:16/9; background:#000;">
            @if($live->embed_url)
                <iframe src="{{ $live->embed_url }}" style="width:100%; height:100%;" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
            @else
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                    <p style="color:#4a5568;">Stream starting soon...</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Upcoming --}}
    @if($upcoming->count())
    <div class="mb-12">
        <h2 style="font-family:'Cinzel',serif; font-size:1.5rem; font-weight:700; color:white; margin-bottom:20px;">
            Upcoming Streams
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcoming as $stream)
            <div class="rounded-xl p-5 hover:border-gold transition-all duration-200" style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium text-yellow-300" style="background:rgba(251,191,36,0.1);">📅 Scheduled</span>
                    <span class="text-xs text-gray-400">{{ ucfirst($stream->platform) }}</span>
                </div>
                <h3 class="font-semibold text-white mb-2">{{ $stream->title }}</h3>
                @if($stream->description)
                    <p class="text-xs text-gray-400 mb-3">{{ Str::limit($stream->description, 80) }}</p>
                @endif
                @if($stream->scheduled_at)
                    <p class="text-xs text-gold">{{ $stream->scheduled_at->format('D, d M Y — H:i') }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Past Streams --}}
    @if($past->count())
    <div>
        <h2 style="font-family:'Cinzel',serif; font-size:1.5rem; font-weight:700; color:white; margin-bottom:20px;">
            Past Streams
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($past as $stream)
            <a href="{{ route('stream.show', $stream) }}" class="block rounded-xl overflow-hidden hover:border-gold transition-all duration-200" style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">
                @if($stream->thumbnail)
                    <img src="{{ asset('storage/'.$stream->thumbnail) }}" class="w-full h-36 object-cover">
                @else
                    <div class="w-full h-36 flex items-center justify-center" style="background:rgba(5,13,26,0.7);">
                        <span class="text-4xl">📺</span>
                    </div>
                @endif
                <div class="p-4">
                    <span class="text-xs text-gray-500 mb-1 block">{{ ucfirst($stream->platform) }}</span>
                    <h3 class="font-semibold text-white text-sm mb-1">{{ $stream->title }}</h3>
                    @if($stream->started_at)
                        <p class="text-xs text-gray-500">{{ $stream->started_at->format('d M Y') }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $past->links() }}</div>
    </div>
    @endif

    @if(!$live && $upcoming->isEmpty() && $past->isEmpty())
    <div class="text-center py-24">
        <p class="text-5xl mb-4">📺</p>
        <h2 style="font-family:'Cinzel',serif; font-size:1.5rem; color:white; margin-bottom:8px;">No Streams Yet</h2>
        <p class="text-gray-400">Check back soon for upcoming live streams.</p>
    </div>
    @endif

</div>
@endsection
