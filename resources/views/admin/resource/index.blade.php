@extends('layouts.admin')
@section('title', 'Resources')
@section('page-title', 'Resource Management')

@section('content')

<div style="display:flex; gap:12px; justify-content:flex-end; margin-bottom:20px;">
    <a href="{{ route('admin.resources.categories') }}" class="btn-secondary" style="padding:9px 18px; display:inline-block;">Manage Categories</a>
    <a href="{{ route('admin.resources.create') }}" class="btn-gold">+ Upload Resource</a>
</div>

<div class="admin-card" style="overflow:hidden;">
    <table>
        <thead>
            <tr>
                <th>Resource</th>
                <th>Category</th>
                <th>Type</th>
                <th>Size</th>
                <th>Downloads</th>
                <th>Visibility</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resources as $resource)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="font-size:20px;">{{ $resource->file_icon }}</span>
                        <div>
                            <p style="color:white; font-weight:500;">{{ $resource->title }}</p>
                            <p style="font-size:11px; color:#6b7280;">{{ $resource->uploader?->full_name }}</p>
                        </div>
                    </div>
                </td>
                <td>{{ $resource->category?->name ?? '—' }}</td>
                <td><span style="color:#c9a227; font-size:12px; text-transform:uppercase; font-weight:600;">{{ $resource->file_type }}</span></td>
                <td>{{ $resource->file_size }}</td>
                <td>{{ number_format($resource->download_count) }}</td>
                <td>
                    @if($resource->is_public)
                        <span style="font-size:11px; color:#34d399; background:rgba(52,211,153,0.1); padding:2px 8px; border-radius:999px;">Public</span>
                    @else
                        <span style="font-size:11px; color:#fbbf24; background:rgba(251,191,36,0.1); padding:2px 8px; border-radius:999px;">Private</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex; gap:6px;">
                        <a href="{{ $resource->file_url }}" target="_blank" class="btn-secondary" style="padding:4px 12px;">View</a>
                        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:40px; color:#4a5568;">No resources yet. <a href="{{ route('admin.resources.create') }}" style="color:#c9a227;">Upload one →</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $resources->links() }}</div>
@endsection
