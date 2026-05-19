<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OFCC Online Outreach Platform')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CDN (use Vite in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:  { DEFAULT: '#0a1628', 50: '#e8ecf2', 900: '#050d1a' },
                        gold:  { DEFAULT: '#c9a227', light: '#f0c84a', dark: '#a07c10' },
                        slate: { ofcc: '#1e2d4a' },
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
        .font-cinzel { font-family: 'Cinzel', serif; }
        .glass { background: rgba(30,45,74,0.7); backdrop-filter: blur(16px); border: 1px solid rgba(201,162,39,0.15); }
        .gold-gradient { background: linear-gradient(135deg, #c9a227, #f0c84a); }
        .text-gold { color: #c9a227; }
        .border-gold { border-color: #c9a227; }
        .btn-gold { background: linear-gradient(135deg, #c9a227, #f0c84a); color: #0a1628; font-weight: 700; padding: 0.625rem 1.5rem; border-radius: 0.5rem; transition: all 0.2s; display: inline-block; }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(201,162,39,0.4); }
        .btn-outline { border: 1px solid #c9a227; color: #c9a227; padding: 0.625rem 1.5rem; border-radius: 0.5rem; transition: all 0.2s; display: inline-block; }
        .btn-outline:hover { background: rgba(201,162,39,0.1); }
        .live-badge { background: #ef4444; animation: pulse 2s infinite; border-radius: 9999px; padding: 2px 10px; font-size: 0.75rem; font-weight: 700; color: white; display: inline-flex; align-items: center; gap: 4px; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        .nav-link { color: #9bb0c9; transition: color 0.2s; font-size: 0.875rem; font-weight: 500; }
        .nav-link:hover { color: #c9a227; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">

{{-- Navigation --}}
<nav class="sticky top-0 z-50" style="background: rgba(10,22,40,0.95); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(201,162,39,0.2);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12">
                        <img src="/images/ofcc.png" alt="logo" class="w-full h-full object-contain">
                    </div>

                    <div class="flex flex-col justify-center">
                        <span class="font-cinzel font-bold text-white text-sm tracking-wide">OFCC</span>
                        <p class="text-[10px] text-gold leading-none mt-0.5">Online Outreach</p>
                    </div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
                <a href="{{ isset($liveLivestream) && $liveLivestream
    ? route('stream.view', $liveLivestream->id)
    : route('livestreams') }}"
   class="nav-link">
Live Stream
</a>
           </div>

            {{-- Auth --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Only show Dashboard if the user is an Admin --}}
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn-outline text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="btn-gold text-sm">Join Us</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 pt-4">
        <div class="bg-green-900/50 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg text-sm">
            ✓ {{ session('success') }}
        </div>
    </div>
@endif
@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 pt-4">
        <div class="bg-red-900/50 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg text-sm">
            ✗ {{ session('error') }}
        </div>
    </div>
@endif

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer style="background: #050d1a; border-top: 1px solid rgba(201,162,39,0.15);" class="mt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg gold-gradient flex items-center justify-center">
                        <span class="text-navy font-bold font-cinzel">✝</span>
                    </div>
                    <span class="font-cinzel font-bold text-white">OFCC</span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">Spreading the gospel and transforming lives through digital outreach, community engagement, and spiritual growth.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                <div class="space-y-2">
                    <a href="{{ route('home') }}" class="block text-sm text-gray-400 hover:text-gold transition-colors">Home</a>
                    <a href="{{ route('livestreams') }}" class="block text-sm text-gray-400 hover:text-gold transition-colors">Live Streams</a>
                    <a href="{{ route('events') }}" class="block text-sm text-gray-400 hover:text-gold transition-colors">Events</a>
                    <a href="{{ route('register') }}" class="block text-sm text-gray-400 hover:text-gold transition-colors">Register</a>
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-4">Connect With Us</h4>
                <div class="space-y-2 text-sm text-gray-400">
                    <p>📧 info@ofcc.org</p>
                    <p>📞 +234 000 0000 000</p>
                    <p>📍 Lagos, Nigeria</p>
                </div>
            </div>
        </div>
        <div class="mt-10 pt-6" style="border-top: 1px solid rgba(201,162,39,0.1);">
            <p class="text-center text-sm text-gray-500">© {{ date('Y') }} OFCC Online Outreach Platform. All rights reserved.</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
