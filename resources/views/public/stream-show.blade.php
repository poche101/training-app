@extends('layouts.app')
@section('title', $livestream->title . ' — OFCC')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <a href="{{ route('livestreams') }}" class="text-sm text-gold hover:underline inline-block mb-6">← All Streams</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Video Player --}}
        <div class="lg:col-span-2">
            <div style="background:#000; border-radius:12px; overflow:hidden; aspect-ratio:16/9; border:1px solid rgba(201,162,39,0.15);">
                @if($livestream->embed_url && $livestream->status !== 'scheduled')
                    <iframe src="{{ $livestream->embed_url }}" style="width:100%; height:100%;" frameborder="0" allowfullscreen allow="autoplay; encrypted-media; picture-in-picture"></iframe>
                @else
                    <div style="width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px;">
                        <span style="font-size:48px;">📺</span>
                        @if($livestream->status === 'scheduled')
                            <p style="color:#c9a227; font-weight:600;">Stream scheduled for {{ $livestream->scheduled_at?->format('D, d M Y — H:i') }}</p>
                        @elseif($livestream->status === 'ended')
                            <p style="color:#6b7280;">This stream has ended.</p>
                        @else
                            <p style="color:#6b7280;">Stream URL not configured.</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="mt-4 p-4 rounded-xl" style="background:rgba(14,25,46,0.8); border:1px solid rgba(201,162,39,0.1);">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        @if($livestream->status === 'live')
                            <span class="live-badge mr-2">● LIVE</span>
                        @endif
                        <h1 class="text-xl font-bold text-white mt-2">{{ $livestream->title }}</h1>
                        @if($livestream->description)
                            <p class="text-sm text-gray-400 mt-2 leading-relaxed">{{ $livestream->description }}</p>
                        @endif
                    </div>
                    <span class="text-xs text-gray-500 flex-shrink-0 mt-1">{{ ucfirst($livestream->platform) }}</span>
                </div>
            </div>

            {{-- Reactions (for authenticated users during live streams) --}}
            @if($livestream->status === 'live' && auth()->check())
            <div class="mt-3 p-3 rounded-xl flex items-center gap-3" style="background:rgba(14,25,46,0.5); border:1px solid rgba(201,162,39,0.08);">
                <span class="text-xs text-gray-400">React:</span>
                @foreach(['🙌','🔥','❤️','🎉','🙏','⚡'] as $emoji)
                <form method="POST" action="{{ route('livestreams') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="emoji" value="{{ $emoji }}">
                    <input type="hidden" name="livestream_id" value="{{ $livestream->id }}">
                    <button type="button" class="text-xl hover:scale-125 transition-transform cursor-pointer border-none bg-transparent">{{ $emoji }}</button>
                </form>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Live Chat Sidebar --}}
        <div style="background:rgba(8,15,31,0.95); border:1px solid rgba(201,162,39,0.15); border-radius:12px; display:flex; flex-direction:column; height:500px;">
            <div style="padding:14px 16px; border-bottom:1px solid rgba(201,162,39,0.1); flex-shrink:0;">
                <p style="font-size:13px; font-weight:600; color:white;">
                    {{ $livestream->status === 'live' ? '💬 Live Chat' : '💬 Chat' }}
                </p>
            </div>

            {{-- Messages --}}
            <div id="chat-messages" style="flex:1; overflow-y:auto; padding:12px; display:flex; flex-direction:column; gap:8px;">
                @forelse($livestream->chatMessages()->with('user')->where('is_hidden', false)->latest()->take(50)->get()->reverse() as $msg)
                <div style="display:flex; gap:8px; align-items:flex-start;">
                    <div style="width:24px; height:24px; border-radius:50%; background:linear-gradient(135deg,#c9a227,#f0c84a); display:flex; align-items:center; justify-content:center; color:#080f1f; font-weight:700; font-size:10px; flex-shrink:0;">
                        {{ strtoupper(substr($msg->user?->full_name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <span style="font-size:11px; font-weight:600; color:#c9a227;">{{ $msg->user?->full_name }}</span>
                        @if($msg->is_pinned) <span style="font-size:9px; color:#fbbf24;">📌</span> @endif
                        <p style="font-size:12px; color:#d1d5db; margin-top:1px; line-height:1.4;">{{ $msg->message }}</p>
                    </div>
                </div>
                @empty
                <p style="font-size:12px; color:#4a5568; text-align:center; margin-top:auto;">No messages yet. Be the first!</p>
                @endforelse
            </div>

            {{-- Input --}}
            @auth
                @if($livestream->status === 'live')
                <div style="padding:10px; border-top:1px solid rgba(201,162,39,0.1); flex-shrink:0;">
                    <form method="POST" action="#" style="display:flex; gap:6px;">
                        @csrf
                        <input type="hidden" name="livestream_id" value="{{ $livestream->id }}">
                        <input type="text" name="message" placeholder="Type a message..." maxlength="200"
                            style="flex:1; background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2); border-radius:8px; padding:8px 10px; color:white; font-size:12px;">
                        <button type="submit" style="background:linear-gradient(135deg,#c9a227,#f0c84a); color:#080f1f; border:none; padding:8px 14px; border-radius:8px; font-weight:700; font-size:12px; cursor:pointer;">Send</button>
                    </form>
                </div>
                @else
                <div style="padding:12px; border-top:1px solid rgba(201,162,39,0.1); text-align:center;">
                    <p style="font-size:11px; color:#4a5568;">Chat is available during live streams.</p>
                </div>
                @endif
            @else
            <div style="padding:12px; border-top:1px solid rgba(201,162,39,0.1); text-align:center;">
                <a href="{{ route('login') }}" style="font-size:12px; color:#c9a227; text-decoration:underline;">Login to chat →</a>
            </div>
            @endauth
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll chat to bottom
    const chatEl = document.getElementById('chat-messages');
    if (chatEl) chatEl.scrollTop = chatEl.scrollHeight;
</script>
@endpush
