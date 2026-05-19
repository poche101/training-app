<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Member Portal') — OFCC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { DEFAULT: '#0a1628', 50: '#e8ecf2', 900: '#050d1a' },
                        gold: { DEFAULT: '#c9a227', light: '#f0c84a', dark: '#a07c10' },
                    },
                    fontFamily: {
                        cinzel: ['Cinzel', 'serif'],
                        inter:  ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #0a1628; color: #e8ecf2; }
        .sidebar { background: rgba(5,13,26,0.95); border-right: 1px solid rgba(201,162,39,0.15); }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 8px; color: #9bb0c9; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; margin-bottom: 2px; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(201,162,39,0.1); color: #c9a227; }
        .glass { background: rgba(30,45,74,0.5); border: 1px solid rgba(201,162,39,0.12); }
        .gold-gradient { background: linear-gradient(135deg, #c9a227, #f0c84a); }
        .text-gold { color: #c9a227; }
        .btn-gold { background: linear-gradient(135deg, #c9a227, #f0c84a); color: #0a1628; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 0.5rem; transition: all 0.2s; display: inline-block; font-size: 0.875rem; }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(201,162,39,0.35); }
        .card { background: rgba(14,25,46,0.8); border: 1px solid rgba(201,162,39,0.1); border-radius: 12px; padding: 1.25rem; }
        .live-badge { background: #ef4444; animation: pulse 2s infinite; border-radius: 9999px; padding: 2px 10px; font-size: 0.7rem; font-weight: 700; color: white; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
    </style>
    @stack('styles')
</head>
<body class="flex h-screen overflow-hidden">

{{-- Sidebar --}}
<aside class="sidebar w-64 flex-shrink-0 flex flex-col h-screen sticky top-0">
    <div class="p-5" style="border-bottom: 1px solid rgba(201,162,39,0.15);">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg gold-gradient flex items-center justify-center">
                <span class="text-navy font-bold font-cinzel text-sm">✝</span>
            </div>
            <div>
                <span class="font-cinzel font-bold text-white text-sm">OFCC</span>
                <p class="text-xs text-gold leading-none mt-0.5">Member Portal</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto p-4">
        <p class="text-xs text-gray-500 uppercase tracking-widest mb-3 px-2">Navigation</p>
        <a href="{{ route('member.dashboard') }}" class="sidebar-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <span>⊞</span> Dashboard
        </a>
        <a href="{{ route('member.resources') }}" class="sidebar-link {{ request()->routeIs('member.resources*') ? 'active' : '' }}">
            <span>📚</span> Resources
        </a>
        <a href="{{ route('member.announcements') }}" class="sidebar-link {{ request()->routeIs('member.announcements') ? 'active' : '' }}">
            <span>📢</span> Announcements
        </a>
        <a href="{{ route('member.events') }}" class="sidebar-link {{ request()->routeIs('member.events') ? 'active' : '' }}">
            <span>📅</span> Events
        </a>
        <a href="{{ route('livestreams') }}" class="sidebar-link">
            <span>📺</span> Live Streams
        </a>
        <a href="{{ route('member.offerings') }}" class="sidebar-link {{ request()->routeIs('member.offerings') ? 'active' : '' }}">
            <span>💰</span> Offerings
        </a>

        <div class="mt-6 pt-4" style="border-top: 1px solid rgba(201,162,39,0.1);">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-3 px-2">Account</p>
            <a href="{{ route('member.profile') }}" class="sidebar-link {{ request()->routeIs('member.profile') ? 'active' : '' }}">
                <span>👤</span> Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left">
                    <span>↩</span> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="p-4 m-3 rounded-xl" style="background: rgba(201,162,39,0.08); border: 1px solid rgba(201,162,39,0.15);">
    <p class="text-xs font-semibold text-gold mb-0.5">{{ auth()->user()?->full_name ?? 'Guest User' }}</p>
    <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()?->role ?? 'guest') }}</p>
</div>
</aside>

{{-- Main --}}
<div class="flex-1 flex flex-col overflow-hidden">
    {{-- Top bar --}}
    <header class="h-14 flex items-center justify-between px-6" style="background: rgba(5,13,26,0.8); border-bottom: 1px solid rgba(201,162,39,0.12);">
        <h1 class="text-sm font-semibold text-white">@yield('page-title', 'Dashboard')</h1>
        <div class="flex items-center gap-3 text-sm text-gray-400">
            <span>{{ now()->format('l, d M Y') }}</span>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-6">
        @if(session('success'))
            <div class="mb-4 bg-green-900/40 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-900/40 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg text-sm">
                ✗ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
