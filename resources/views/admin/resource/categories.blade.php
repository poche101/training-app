@extends('layouts.admin')
@section('title', 'Resource Categories')
@section('page-title', 'Resource Categories')

@section('content')
<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; max-width:900px;">

    {{-- Category List --}}
    <div class="admin-card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:600; color:white; margin-bottom:16px;">All Categories</h3>
        @forelse($categories as $cat)
        <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid rgba(201,162,39,0.07);">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:18px;">{{ $cat->icon ?? '📁' }}</span>
                <div>
                    <p style="font-size:13px; color:white; font-weight:500;">{{ $cat->name }}</p>
                    <p style="font-size:11px; color:#6b7280;">{{ $cat->resources_count }} resource{{ $cat->resources_count !== 1 ? 's' : '' }}</p>
                </div>
            </div>
        </div>
        @empty
        <p style="font-size:13px; color:#6b7280;">No categories yet.</p>
        @endforelse
    </div>

    {{-- Create Category --}}
    <div class="admin-card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:600; color:white; margin-bottom:16px;">Add New Category</h3>
        <form method="POST" action="{{ route('admin.resources.categories.store') }}">
            @csrf
            <div style="display:grid; gap:16px;">
                <div>
                    <label>Category Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Training Manuals">
                    @error('name')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label>Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="📚" maxlength="4">
                </div>
                <button type="submit" class="btn-gold" style="padding:9px 20px;">Create Category</button>
            </div>
        </form>
    </div>
</div>
@endsection
