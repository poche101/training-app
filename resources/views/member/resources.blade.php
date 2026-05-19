@extends('layouts.member')
@section('title', 'Resources')
@section('page-title', 'Training Resources')

@section('content')

{{-- Search and Filter --}}
<div class="card mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="text-xs text-gray-400 mb-1 block">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resources..." style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2); border-radius:8px; padding:8px 12px; color:white; width:100%; font-size:0.875rem;">
        </div>
        <div class="min-w-40">
            <label class="text-xs text-gray-400 mb-1 block">Category</label>
            <select name="category" style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2); border-radius:8px; padding:8px 12px; color:white; width:100%; font-size:0.875rem;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->resources_count }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-gold text-sm px-5 py-2">Filter</button>
        @if(request()->hasAny(['search','category']))
            <a href="{{ route('member.resources') }}" class="btn-outline text-sm px-4 py-2">Clear</a>
        @endif
    </form>
</div>

{{-- Resources Grid --}}
@if($resources->count())
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
    @foreach($resources as $resource)
    <div class="card hover:border-gold transition-colors duration-200" style="border-color: rgba(201,162,39,0.1);">
        <div class="flex items-start gap-3">
            <div class="text-3xl flex-shrink-0 mt-0.5">{{ $resource->file_icon }}</div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-white text-sm mb-1 truncate">{{ $resource->title }}</h3>
                @if($resource->description)
                    <p class="text-xs text-gray-400 leading-relaxed mb-2">{{ Str::limit($resource->description, 80) }}</p>
                @endif
                <div class="flex items-center gap-2 flex-wrap">
                    @if($resource->category)
                        <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(201,162,39,0.1); color:#c9a227;">{{ $resource->category->name }}</span>
                    @endif
                    <span class="text-xs text-gray-500">{{ strtoupper($resource->file_type) }}</span>
                    <span class="text-xs text-gray-500">{{ $resource->file_size }}</span>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-3 flex items-center justify-between" style="border-top:1px solid rgba(201,162,39,0.08);">
            <span class="text-xs text-gray-500">↓ {{ number_format($resource->download_count) }} downloads</span>
            <a href="{{ route('member.resources.download', $resource) }}" class="btn-gold text-xs px-4 py-1.5">Download</a>
        </div>
    </div>
    @endforeach
</div>
{{ $resources->links() }}
@else
<div class="card text-center py-12">
    <p class="text-4xl mb-4">📚</p>
    <p class="text-gray-400">No resources found.</p>
    @if(request()->hasAny(['search','category']))
        <a href="{{ route('member.resources') }}" class="text-gold text-sm hover:underline mt-2 inline-block">Clear filters →</a>
    @endif
</div>
@endif

@endsection
