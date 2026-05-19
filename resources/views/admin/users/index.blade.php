@extends('layouts.admin')
@section('title', 'Users')
@section('page-title', 'User Management')

@section('content')

{{-- Filter Bar --}}
<div class="admin-card" style="padding:16px 20px; margin-bottom:20px;">
    <form method="GET" style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
        <div style="flex:1; min-width:200px;">
            <label>Search Members</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email...">
        </div>
        <div style="min-width:160px;">
            <label>Filter by Role</label>
            <select name="role">
                <option value="">All Roles</option>
                <option value="member"           {{ request('role')=='member'           ?'selected':'' }}>Member</option>
                <option value="moderator"        {{ request('role')=='moderator'        ?'selected':'' }}>Moderator</option>
                <option value="resource_manager" {{ request('role')=='resource_manager' ?'selected':'' }}>Resource Manager</option>
                <option value="media_admin"      {{ request('role')=='media_admin'      ?'selected':'' }}>Media Admin</option>
                <option value="outreach_admin"   {{ request('role')=='outreach_admin'   ?'selected':'' }}>Outreach Admin</option>
                <option value="super_admin"      {{ request('role')=='super_admin'      ?'selected':'' }}>Super Admin</option>
            </select>
        </div>
        <button type="submit" class="btn-gold" style="padding:9px 20px;">Search</button>
        @if(request()->hasAny(['search','role']))
            <a href="{{ route('admin.users.index') }}" class="btn-secondary" style="padding:9px 16px; display:inline-block;">Clear</a>
        @endif
    </form>
</div>

<div class="admin-card" style="overflow:hidden;">
    <table>
        <thead>
            <tr>
                <th>Member</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Email Verified</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#c9a227,#f0c84a); display:flex; align-items:center; justify-content:center; color:#080f1f; font-weight:700; font-size:13px; flex-shrink:0;">
                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                        </div>
                        <div>
                            <p style="color:white; font-weight:500; font-size:13px;">{{ $user->full_name }}</p>
                            <p style="color:#6b7280; font-size:11px;">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td>{{ $user->phone ?? '—' }}</td>
                <td>
                    @php
                        $roleColors = [
                            'super_admin'      => 'color:#ef4444; background:rgba(239,68,68,0.12);',
                            'outreach_admin'   => 'color:#c9a227; background:rgba(201,162,39,0.12);',
                            'media_admin'      => 'color:#60a5fa; background:rgba(96,165,250,0.12);',
                            'resource_manager' => 'color:#a78bfa; background:rgba(167,139,250,0.12);',
                            'moderator'        => 'color:#34d399; background:rgba(52,211,153,0.12);',
                            'member'           => 'color:#9ca3af; background:rgba(156,163,175,0.1);',
                        ];
                        $rc = $roleColors[$user->role] ?? $roleColors['member'];
                    @endphp
                    <span style="font-size:11px; padding:2px 8px; border-radius:999px; font-weight:600; {{ $rc }}">
                        {{ ucfirst(str_replace('_',' ',$user->role)) }}
                    </span>
                </td>
                <td>
                    @if($user->email_verified_at)
                        <span style="color:#34d399; font-size:12px;">✓ Verified</span>
                    @else
                        <span style="color:#fbbf24; font-size:12px;">⚠ Pending</span>
                    @endif
                </td>
                <td style="font-size:12px;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex; gap:6px;">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary" style="padding:4px 12px;">View</a>
                        @if(auth()->user()->isSuperAdmin() && $user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Permanently delete {{ $user->full_name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:40px; color:#4a5568;">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $users->links() }}</div>
@endsection
