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
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #060f1e;
            color: #e2e8f0;
        }

        .admin-sidebar {
            width: 260px;
            transition: all 0.3s ease;
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #7a90a8;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }

        .sb-link svg {
            width: 18px;
            height: 18px;
            color: #7a90a8;
            transition: color 0.2s;
        }

        .sb-link:hover {
            background: rgba(201, 162, 39, 0.08);
            color: #c9a227;
        }

        .sb-link:hover svg {
            color: #c9a227;
        }

        .sb-link.active {
            background: rgba(201, 162, 39, 0.12);
            color: #f0c84a;
            border-left: 3px solid #c9a227;
        }

        .sb-link.active svg {
            color: #f0c84a;
        }

        .sb-section {
            font-size: 0.7rem;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 18px 0 8px 14px;
        }

        .gold-gradient {
            background: linear-gradient(135deg, #c9a227, #f0c84a);
        }

        .text-gold { color: #c9a227; }

        .admin-card, .stat-card {
            background: rgba(12, 20, 36, 0.9);
            border: 1px solid rgba(201, 162, 39, 0.1);
            border-radius: 12px;
        }

        .btn-gold {
            background: linear-gradient(135deg, #c9a227, #f0c84a);
            color: #080f1f;
            font-weight: 700;
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-gold:hover {
            box-shadow: 0 4px 16px rgba(201, 162, 39, 0.3);
            transform: translateY(-1px);
        }

        /* Mobile Menu */
        #mobile-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        #mobile-sidebar.open {
            transform: translateX(0);
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .badge-live {
            animation: blink 1.5s infinite;
        }

        input, select, textarea {
            background: rgba(12, 20, 36, 0.9);
            border: 1px solid rgba(201, 162, 39, 0.2);
            color: #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            width: 100%;
            outline: none;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #c9a227;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            font-size: 0.75rem;
            color: #c9a227;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid rgba(201, 162, 39, 0.15);
        }

        td {
            padding: 14px;
            border-bottom: 1px solid rgba(201, 162, 39, 0.06);
            font-size: 0.875rem;
        }

        tr:hover td {
            background: rgba(201, 162, 39, 0.03);
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen flex flex-col lg:flex-row">

    <header class="lg:hidden bg-[#080f1f] border-b border-gold/10 px-4 py-3 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <button id="mobile-menu-btn" class="text-2xl text-gold p-1">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                    <img src="/images/ofcc.png" alt="logo" class="w-full h-full object-contain">
                </div>
                <div class="font-cinzel font-bold text-white">OFCC</div>
            </div>
        </div>
        <div class="text-xs text-gray-400">{{ now()->format('d M Y') }}</div>
    </header>

    <aside id="sidebar" class="admin-sidebar hidden lg:flex flex-col bg-[#080f1f] border-r border-gold/10 h-screen sticky top-0 overflow-hidden">

        <div class="p-5 border-b border-gold/10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg gold-gradient flex items-center justify-center">
                    <span style="font-family:'Cinzel',serif; font-weight:700; color:#080f1f; font-size:18px;">✝</span>
                </div>
                <div>
                    <div class="font-cinzel font-bold text-white text-lg tracking-wide">OFCC</div>
                    <div class="text-gold text-xs -mt-1">Admin Control</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <p class="sb-section">Overview</p>
            <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V16zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V16z"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.analytics') }}" class="sb-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Analytics
            </a>

            <p class="sb-section">Content</p>
            <a href="{{ route('admin.livestreams.index') }}" class="sb-link {{ request()->routeIs('admin.livestreams*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Livestreams
            </a>
            <a href="{{ route('admin.resources.index') }}" class="sb-link {{ request()->routeIs('admin.resources*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Resources
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="sb-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                Announcements
            </a>

            <p class="sb-section">Engagement</p>
            <a href="{{ route('admin.events.index') }}" class="sb-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Events
            </a>
            <a href="{{ route('admin.event-registrations.index') }}" class="sb-link {{ request()->routeIs('admin.event-registrations*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                Registrations
            </a>
            <a href="{{ route('admin.testimonies.index') }}" class="sb-link {{ request()->routeIs('admin.testimonies*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                Testimonies
            </a>

            <p class="sb-section">Management</p>
            <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Users
            </a>
            <a href="{{ route('admin.activity-logs') }}" class="sb-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Activity Logs
            </a>
        </nav>

        <div class="p-4 border-t border-gold/10 bg-black/30">
            <a href="{{ route('welcome') }}" class="sb-link mb-1">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h18"></path></svg>
                View Public Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-link w-full text-left">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l4-4m-4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>

            <div class="mt-6 text-xs">
                <p class="font-medium text-white">{{ auth()->user()?->full_name ?? 'Administrator' }}</p>
                <p class="text-gray-500">{{ ucfirst(auth()->user()?->role ?? 'Admin') }}</p>
            </div>
        </div>
    </aside>

    <div id="mobile-sidebar" class="fixed inset-0 bg-black/70 z-50 lg:hidden">
        <div class="w-72 bg-[#080f1f] h-full flex flex-col">
            <div class="p-5 border-b border-gold/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg gold-gradient flex items-center justify-center">
                        <span class="text-[#080f1f] text-xl font-bold">✝</span>
                    </div>
                    <div class="font-cinzel font-bold text-white">OFCC Admin</div>
                </div>
                <button id="close-mobile-menu" class="text-2xl text-gray-400">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <p class="sb-section">Overview</p>
                <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V16zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V16z"></path></svg> Dashboard
                </a>
                <a href="{{ route('admin.analytics') }}" class="sb-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Analytics
                </a>

                <p class="sb-section">Content</p>
                <a href="{{ route('admin.livestreams.index') }}" class="sb-link {{ request()->routeIs('admin.livestreams*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Livestreams
                </a>
                <a href="{{ route('admin.resources.index') }}" class="sb-link {{ request()->routeIs('admin.resources*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg> Resources
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="sb-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg> Announcements
                </a>

                <p class="sb-section">Engagement</p>
                <a href="{{ route('admin.events.index') }}" class="sb-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Events
                </a>
                <a href="{{ route('admin.event-registrations.index') }}" class="sb-link {{ request()->routeIs('admin.event-registrations*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg> Registrations
                </a>
                <a href="{{ route('admin.testimonies.index') }}" class="sb-link {{ request()->routeIs('admin.testimonies*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg> Testimonies
                </a>

                <p class="sb-section">Management</p>
                <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Users
                </a>
                <a href="{{ route('admin.activity-logs') }}" class="sb-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg> Activity Logs
                </a>
            </nav>

            <div class="p-4 border-t border-gold/10 bg-black/30">
                <a href="{{ route('welcome') }}" class="sb-link mb-1">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h18"></path></svg> View Public Site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sb-link w-full text-left">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l4-4m-4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Logout
                    </button>
                </form>

                <div class="mt-6 text-xs">
                    <p class="font-medium text-white">{{ auth()->user()?->full_name ?? 'Administrator' }}</p>
                    <p class="text-gray-500">{{ ucfirst(auth()->user()?->role ?? 'Admin') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col min-h-screen">
        <header class="hidden lg:flex items-center justify-between px-8 py-4 bg-[#080f1f] border-b border-gold/10">
            <h1 class="text-lg font-semibold">@yield('page-title', 'Dashboard')</h1>
            <div class="text-sm text-gray-400">{{ now()->format('D, d M Y') }}</div>
        </header>

        <main class="flex-1 overflow-auto p-4 lg:p-8">
            @if (session('success'))
                <div class="mb-6 bg-green-900/30 border border-green-500/30 text-green-300 px-5 py-3 rounded-xl text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-900/30 border border-red-500/30 text-red-300 px-5 py-3 rounded-xl text-sm">
                    ✗ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Mobile Menu Toggle
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const closeBtn = document.getElementById('close-mobile-menu');

        mobileBtn.addEventListener('click', () => {
            mobileSidebar.classList.add('open');
        });

        closeBtn.addEventListener('click', () => {
            mobileSidebar.classList.remove('open');
        });

        // Close mobile menu when clicking outside
        mobileSidebar.addEventListener('click', (e) => {
            if (e.target === mobileSidebar) {
                mobileSidebar.classList.remove('open');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
