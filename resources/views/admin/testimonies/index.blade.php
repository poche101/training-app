@extends('layouts.admin')
@section('title', 'Testimonies')
@section('page-title', 'Testimonies & Prayer Requests')

@section('content')
<div class="admin-card" style="padding:20px;">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Message</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($testimonies as $testimony)
            <tr>
                <td style="font-weight:500; color:white;">{{ $testimony->author_name }}</td>
                <td style="max-width:300px;">{{ Str::limit($testimony->content, 100) }}</td>
                <td>
                    <span style="font-size:11px; padding:2px 8px; border-radius:999px;
                        {{ $testimony->is_approved
                           ? 'background:rgba(34,197,94,0.1); color:#86efac;'
                           : 'background:rgba(251,191,36,0.1); color:#fbbf24;' }}">
                        {{ $testimony->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </td>
                <td>{{ $testimony->created_at->diffForHumans() }}</td>
                <td style="display:flex; gap:8px;">
                    @if(!$testimony->is_approved)
                    <form method="POST" action="{{ route('admin.testimonies.approve', $testimony) }}">
                        @csrf @method('PATCH')
                        <button class="btn-gold" style="padding:4px 12px; font-size:12px;">Approve</button>
                    </form>
                    @endif

                    {{-- Delete Trigger --}}
                    <button type="button" class="btn-danger"
                            onclick="confirmDelete('{{ route('admin.testimonies.destroy', $testimony) }}')">
                        Delete
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#6b7280; padding:40px;">
                    No testimonies submitted yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $testimonies->links() }}</div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#111827; padding:24px; border-radius:12px; max-width:400px; width:90%; border:1px solid #374151; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
        <h3 style="color:white; font-size:1.25rem; margin-bottom:12px;">Confirm Deletion</h3>
        <p style="color:#9ca3af; margin-bottom:24px;">Are you sure you want to delete this testimony? This action cannot be undone.</p>
        <div style="display:flex; gap:12px; justify-content:flex-end;">
            <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" style="padding:8px 16px; background:#374151; color:white; border-radius:6px; cursor:pointer;">Cancel</button>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <button type="submit" style="padding:8px 16px; background:#dc2626; color:white; border-radius:6px; cursor:pointer;">Yes, Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(url) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = url;
        modal.style.display = 'flex';
    }
</script>
@endsection
