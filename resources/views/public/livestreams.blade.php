@extends('layouts.app')
@section('title', 'Live Streams — OFCC')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Live Section / Default Showcase --}}
    <div class="rounded-2xl overflow-hidden mb-12" style="border:2px solid {{ $live ? 'rgba(239,68,68,0.5)' : 'rgba(212,175,55,0.4)' }}; box-shadow:0 0 40px {{ $live ? 'rgba(239,68,68,0.1)' : 'rgba(14,25,46,0.5)' }};">

        {{-- Header Strip --}}
        <div style="background:rgba(14,25,46,0.8); padding:14px 20px; display:flex; align-items:center; justify-content:space-between;" class="backdrop-blur-md">
            <div class="flex items-center gap-3">
                @if($live)
                    <span class="live-badge">● LIVE NOW</span>
                    <span class="text-white font-semibold">{{ $live->title }}</span>
                @else
                    <span class="px-2.5 py-1 text-[11px] font-bold tracking-wider text-gold bg-gold/10 border border-gold/20 rounded-md uppercase">Outreach Presentation</span>
                    <span class="text-gray-200 font-semibold text-sm sm:text-base">Watch Outreach Highlights</span>
                @endif
            </div>
            @if($live)
                <div class="flex items-center gap-2">
                    <a href="{{ route('stream.show', $live) }}" class="btn-gold text-sm">Watch Live →</a>
                    {{-- Admin Delete Action Trigger --}}
                    @auth
                        <button type="button"
                                onclick="confirmStreamDeletion('{{ route('admin.stream.destroy', $live) }}', '{{ $live->title }}')"
                                class="px-3 py-1.5 rounded-lg border border-red-500/30 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all text-xs font-semibold">
                            Delete
                        </button>
                    @endauth
                </div>
            @endif
        </div>

        {{-- Video Canvas Frame --}}
        <div style="aspect-ratio:16/9; background:#000;" class="w-full relative">
            @if($live)
                @if($live->embed_url)
                    <iframe src="{{ $live->embed_url }}" style="width:100%; height:100%;" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                        <p style="color:#4a5568;">Stream starting soon...</p>
                    </div>
                @endif
            @else
                {{-- Native MP4 Fallback Stream Player --}}
                <video id="heroBgVideo" autoplay muted loop playsinline webkit-playsinline="true" controls disablePictureInPicture disableRemotePlayback preload="auto" class="w-full h-full object-cover">
                    <source src="https://s3.eu-west-2.amazonaws.com/lodams-videoshare/videos/ofcc_601699fe3ccc7b0007cbc451.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif
        </div>
    </div>

    {{-- Upcoming --}}
    @if($upcoming->count())
    <div class="mb-12">
        <h2 style="font-family:'Cinzel',serif; font-size:1.5rem; font-weight:700; color:white; margin-bottom:20px;">
            Upcoming Streams
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcoming as $stream)
            <div class="rounded-xl p-5 hover:border-gold transition-all duration-200 relative group" style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">

                @auth
                    <button type="button"
                            onclick="confirmStreamDeletion('{{ route('admin.stream.destroy', $stream) }}', '{{ $stream->title }}')"
                            class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 p-1.5 rounded-md bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500 hover:text-white transition-all text-xs">
                        🗑️
                    </button>
                @endauth

                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium text-yellow-300" style="background:rgba(251,191,36,0.1);">📅 Scheduled</span>
                    <span class="text-xs text-gray-400">{{ ucfirst($stream->platform) }}</span>
                </div>
                <h3 class="font-semibold text-white mb-2 pr-6">{{ $stream->title }}</h3>
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
            <div class="block rounded-xl overflow-hidden hover:border-gold transition-all duration-200 relative group" style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">

                @auth
                    <button type="button"
                            onclick="confirmStreamDeletion('{{ route('admin.stream.destroy', $stream) }}', '{{ $stream->title }}')"
                            class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 p-1.5 rounded-md bg-red-500/80 border border-red-600 text-white hover:bg-red-600 transition-all text-xs shadow-lg">
                        Delete
                    </button>
                @endauth

                <a href="{{ route('stream.show', $stream) }}" class="block">
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
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $past->links() }}</div>
    </div>
    @endif

    {{-- Empty State --}}
    @if(!$live && $upcoming->isEmpty() && $past->isEmpty())
    <div class="text-center py-24">
        <p class="text-5xl mb-4">📺</p>
        <h2 style="font-family:'Cinzel',serif; font-size:1.5rem; color:white; margin-bottom:8px;">No Streams Yet</h2>
        <p class="text-gray-400">Check back soon for upcoming live streams.</p>
    </div>
    @endif

</div>

{{-- Dynamic Accessible Delete Modal --}}
<div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm transition-all duration-300">
    <div class="w-full max-w-md scale-95 transform rounded-2xl border border-red-500/20 bg-[#0e192e] p-6 shadow-2xl transition-all duration-300">
        <div class="flex items-center gap-3 mb-4 text-red-400">
            <span class="text-2xl">⚠️</span>
            <h3 class="font-cinzel text-lg font-bold text-white">Confirm Stream Deletion</h3>
        </div>

        <p class="text-sm text-gray-300 mb-6 leading-relaxed">
            Are you sure you want to permanently delete <strong id="deleteTargetTitle" class="text-white font-semibold">this stream</strong>? This action cannot be undone.
        </p>

        <form id="deleteStreamForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors bg-slate-900 border border-slate-800 rounded-xl">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-500 active:bg-red-700 border border-red-500 rounded-xl shadow-lg shadow-red-600/20 transition-all">
                    Yes, Delete Stream
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmStreamDeletion(deleteUrl, streamTitle) {
        const modal = document.getElementById('deleteConfirmationModal');
        const form = document.getElementById('deleteStreamForm');
        const titleSpan = document.getElementById('deleteTargetTitle');

        if (modal && form && titleSpan) {
            form.action = deleteUrl;
            titleSpan.textContent = `"${streamTitle}"`;

            // Show modal and apply open animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmationModal');
        if (modal) {
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    }

    // Optional: Close modal if clicking outside the card frame boundary
    document.getElementById('deleteConfirmationModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>
@endsection
