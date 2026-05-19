@extends('layouts.member')
@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')

<div class="space-y-3">
    @forelse($announcements as $ann)
    <div class="card" style="border-color: {{ $ann->is_pinned ? 'rgba(201,162,39,0.3)' : 'rgba(201,162,39,0.1)' }};">
        <div class="flex items-start gap-3">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    @if($ann->is_pinned)
                        <span class="text-xs text-gold font-semibold">📌 Pinned</span>
                    @endif
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $ann->type === 'urgent' ? 'bg-red-900/40 text-red-300' : ($ann->type === 'event' ? 'bg-blue-900/40 text-blue-300' : 'bg-gray-700/50 text-gray-300') }}">
                        {{ ucfirst($ann->type) }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $ann->published_at->diffForHumans() }}</span>
                </div>
                <h3 class="font-bold text-white mb-2">{{ $ann->title }}</h3>
                <p class="text-sm text-gray-300 leading-relaxed">{{ $ann->content }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-16">
        <p class="text-4xl mb-4">📢</p>
        <p class="text-gray-400">No announcements at the moment.</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $announcements->links() }}
</div>

@endsection
