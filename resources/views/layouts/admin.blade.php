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
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #7a90a8;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }

        .sb-link:hover {
            background: rgba(201, 162, 39, 0.08);
            color: #c9a227;
        }

        .sb-link.active {
            background: rgba(201, 162, 39, 0.12);
            color: #f0c84a;
            border-left: 3px solid #c9a227;
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

    <!-- Mobile Topbar -->
    <header class="lg:hidden bg-[#080f1f] border-b border-gold/10 px-4 py-3 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <button id="mobile-menu-btn" class="text-2xl text-gold p-1">
                ☰
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

    <!-- Sidebar -->
    <aside id="sidebar"
           class="admin-sidebar hidden lg:flex flex-col bg-[#080f1f] border-r border-gold/10 h-screen sticky top-0 overflow-hidden">

        <!-- Logo -->
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

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <p class="sb-section">Overview</p>
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
            <a href="{{ route('admin.testimonies.index') }}" class="sb-link {{ request()->routeIs('admin.testimonies*') ? 'active' : '' }}">
                <span>🙏</span> Testimonies
            </a>

            <p class="sb-section">Management</p>
            <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span>👥</span> Users
            </a>
            <a href="{{ route('admin.activity-logs') }}" class="sb-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                <span>📋</span> Activity Logs
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-gold/10 bg-black/30">
            <a href="{{ route('home') }}" class="sb-link mb-1">
                <span>🌐</span> View Public Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-link w-full text-left">
                    <span>↩</span> Logout
                </button>
            </form>

            <div class="mt-6 text-xs">
                <p class="font-medium text-white">{{ auth()->user()?->full_name ?? 'Administrator' }}</p>
                <p class="text-gray-500">{{ ucfirst(auth()->user()?->role ?? 'Admin') }}</p>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-sidebar" class="fixed inset-0 bg-black/70 z-50 lg:hidden">
        <div class="w-72 bg-[#080f1f] h-full flex flex-col">
            <!-- Header -->
            <div class="p-5 border-b border-gold/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg gold-gradient flex items-center justify-center">
                        <span class="text-[#080f1f] text-2xl">✝</span>
                    </div>
                    <div class="font-cinzel font-bold text-white">OFCC Admin</div>
                </div>
                <button id="close-mobile-menu" class="text-3xl text-gray-400">✕</button>
            </div>

            <!-- Nav Content -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <p class="sb-section">Overview</p>
                <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span>⊞</span> Dashboard</a>
                <a href="{{ route('admin.analytics') }}" class="sb-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}"><span>📊</span> Analytics</a>

                <p class="sb-section">Content</p>
                <a href="{{ route('admin.livestreams.index') }}" class="sb-link {{ request()->routeIs('admin.livestreams*') ? 'active' : '' }}"><span>📺</span> Livestreams</a>
                <a href="{{ route('admin.resources.index') }}" class="sb-link {{ request()->routeIs('admin.resources*') ? 'active' : '' }}"><span>📚</span> Resources</a>
                <a href="{{ route('admin.announcements.index') }}" class="sb-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}"><span>📢</span> Announcements</a>

                <p class="sb-section">Engagement</p>
                <a href="{{ route('admin.events.index') }}" class="sb-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}"><span>📅</span> Events</a>
                <a href="{{ route('admin.testimonies.index') }}" class="sb-link {{ request()->routeIs('admin.testimonies*') ? 'active' : '' }}"><span>🙏</span> Testimonies</a>

                <p class="sb-section">Management</p>
                <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><span>👥</span> Users</a>
                <a href="{{ route('admin.activity-logs') }}" class="sb-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}"><span>📋</span> Activity Logs</a>
            </nav>

            <!-- Mobile Footer -->
            <div class="p-4 border-t border-gold/10 bg-black/30">
                <a href="{{ route('home') }}" class="sb-link mb-1">
                    <span>🌐</span> View Public Site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sb-link w-full text-left">
                        <span>↩</span> Logout
                    </button>
                </form>

                <div class="mt-6 text-xs">
                    <p class="font-medium text-white">{{ auth()->user()?->full_name ?? 'Administrator' }}</p>
                    <p class="text-gray-500">{{ ucfirst(auth()->user()?->role ?? 'Admin') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Desktop Topbar -->
        <header class="hidden lg:flex items-center justify-between px-8 py-4 bg-[#080f1f] border-b border-gold/10">
            <h1 class="text-lg font-semibold">@yield('page-title', 'Dashboard')</h1>
            <div class="text-sm text-gray-400">{{ now()->format('D, d M Y') }}</div>
        </header>

        <!-- Page Content -->
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
