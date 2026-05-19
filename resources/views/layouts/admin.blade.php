<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — OFCC</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #060f1e; color: #e2e8f0; min-height: 100vh; display: flex; overflow: hidden; margin: 0; }
        .admin-sidebar { width: 260px; flex-shrink: 0; background: #080f1f; border-right: 1px solid rgba(201,162,39,0.12); height: 100vh; position: sticky; top: 0; display: flex; flex-direction: column; }
        .sb-nav-container { flex: 1; overflow-y: auto; padding: 12px 10px; display: flex; flex-direction: column; }
        .sb-link { display: flex; align-items: center; gap: 10px; padding: 9px 14px; border-radius: 8px; color: #7a90a8; font-size: 0.82rem; font-weight: 500; transition: all 0.2s; margin-bottom: 2px; text-decoration: none; }
        .sb-link:hover { background: rgba(201,162,39,0.08); color: #c9a227; }
        .sb-link.active { background: rgba(201,162,39,0.12); color: #f0c84a; border-left: 3px solid #c9a227; }
        .sb-section { font-size: 0.7rem; color: #4a5568; text-transform: uppercase; letter-spacing: 0.08em; margin: 16px 0 6px 14px; }
        .gold-gradient { background: linear-gradient(135deg, #c9a227, #f0c84a); }
        .text-gold { color: #c9a227; }
        .admin-card { background: rgba(12,20,36,0.9); border: 1px solid rgba(201,162,39,0.1); border-radius: 12px; }
        .btn-gold { background: linear-gradient(135deg, #c9a227, #f0c84a); color: #080f1f; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.875rem; transition: all 0.2s; display: inline-block; }
        .btn-gold:hover { box-shadow: 0 4px 16px rgba(201,162,39,0.3); transform: translateY(-1px); }
        .btn-danger { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 0.4rem 1rem; border-radius: 6px; font-size: 0.8rem; transition: all 0.2s; }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .btn-secondary { background: rgba(30,45,74,0.8); border: 1px solid rgba(201,162,39,0.2); color: #c9a227; padding: 0.4rem 1rem; border-radius: 6px; font-size: 0.8rem; transition: all 0.2s; }
        .stat-card { background: rgba(12,20,36,0.9); border: 1px solid rgba(201,162,39,0.1); border-radius: 12px; padding: 1.25rem; }
        .badge-live { background: #ef4444; color: white; font-size: 0.65rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; animation: blink 1.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1}50%{opacity:0.6} }
        input, select, textarea { background: rgba(12,20,36,0.8); border: 1px solid rgba(201,162,39,0.2); color: #e2e8f0; border-radius: 8px; padding: 8px 12px; width: 100%; font-size: 0.875rem; outline: none; transition: border-color 0.2s; }
        input:focus, select:focus, textarea:focus { border-color: #c9a227; }
        label { font-size: 0.8rem; color: #9bb0c9; font-weight: 500; display: block; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 0.75rem; color: #c9a227; text-transform: uppercase; letter-spacing: 0.05em; padding: 10px 14px; text-align: left; border-bottom: 1px solid rgba(201,162,39,0.15); }
        td { padding: 12px 14px; border-bottom: 1px solid rgba(201,162,39,0.06); font-size: 0.875rem; color: #b0c4de; }
        tr:hover td { background: rgba(201,162,39,0.03); }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="admin-sidebar">
    <div style="padding: 18px 16px; border-bottom: 1px solid rgba(201,162,39,0.12); flex-shrink: 0;">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg gold-gradient flex items-center justify-center flex-shrink-0">
                <span style="font-family:'Cinzel',serif; font-weight:700; color:#080f1f; font-size:14px;">✝</span>
            </div>
            <div>
                <div style="font-family:'Cinzel',serif; font-weight:700; color:white; font-size:13px;">OFCC Admin</div>
                <div class="text-gold" style="font-size:10px;">Control Panel</div>
            </div>
        </div>
    </div>

    {{-- Native Scrollable Container Pipeline --}}
    <nav class="sb-nav-container">
        <p class="sb-section" style="margin-top: 4px;">Overview</p>
        <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span>⊞</span> Dashboard
        </a>
        <a href="{{ route('admin.analytics') }}" class="sb-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <span>📊</span> Analytics
        </a>

        <p class="sb-section">Content</p>
        <a href="{{ route('admin.livestreams.index') }}" class="sb-link {{ request()->routeIs('admin.livestreams*') ? 'active' : '' }}">
            <span>📺</span> Livestreams
        </a>
        <a href="{{ route('admin.resources.index') }}" class="sb-link {{ request()->routeIs('admin.resources*') ? 'active' : '' }}">
            <span>📚</span> Resources
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="sb-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
            <span>📢</span> Announcements
        </a>

        <p class="sb-section">Engagement</p>
        <a href="{{ route('admin.events.index') }}" class="sb-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
            <span>📅</span> Events
        </a>

        <p class="sb-section">Management</p>
        <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <span>👥</span> Users
        </a>

        <a href="{{ route('admin.activity-logs') }}" class="sb-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
            <span>📋</span> Activity Logs
        </a>

        {{-- Footer Controls --}}
        <div style="margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(201,162,39,0.1);">
            <a href="{{ route('home') }}" class="sb-link">
                <span>🌐</span> View Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-link w-full text-left" style="background: none; border: none; cursor: pointer;">
                    <span>↩</span> Logout
                </button>
            </form>
        </div>
    </nav>

    <div style="padding: 12px; background: rgba(201,162,39,0.07); border-top: 1px solid rgba(201,162,39,0.15); flex-shrink: 0;">
        <p style="margin: 0; font-size: 11px; font-weight: 600; color: #c9a227; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ auth()->user()?->full_name ?? 'Administrator' }}
        </p>
        <p style="margin: 2px 0 0 0; font-size: 11px; color: #6b7280;">
            {{ ucfirst(auth()->user()?->role ?? 'Admin Guest') }}
        </p>
    </div>
</aside>

{{-- Content Arena --}}
<div style="flex:1; display:flex; flex-direction:column; overflow:hidden; height:100vh;">
    {{-- Topbar --}}
    <header style="height:56px; display:flex; align-items:center; justify-content:space-between; padding:0 24px; background:rgba(6,15,30,0.9); border-bottom:1px solid rgba(201,162,39,0.1); flex-shrink:0;">
        <h1 style="font-size:14px; font-weight:600; color:white; margin:0;">@yield('page-title', 'Dashboard')</h1>
        <span style="font-size:12px; color:#4a5568;">{{ now()->format('D, d M Y') }}</span>
    </header>

    <main style="flex:1; overflow-y:auto; padding:24px;">
        @if(session('success'))
            <div style="margin-bottom:16px; background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.25); color:#86efac; padding:12px 16px; border-radius:8px; font-size:13px;">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="margin-bottom:16px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.25); color:#fca5a5; padding:12px 16px; border-radius:8px; font-size:13px;">
                ✗ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
