@extends('layouts.app')

@section('title', 'OFCC — Online Outreach Platform')

{{-- Force override to ensure no navbar elements from layouts.app render visible --}}
<style>
    nav, .navbar, header, #main-navbar { display: none !important; }
</style>

@section('content')

@php
    $liveLivestream = $liveLivestream ?? null;
@endphp

{{-- Hero Section --}}
<section class="relative min-h-screen flex items-center overflow-hidden"
         style="background: linear-gradient(180deg, rgba(5,13,26,0.9) 0%, rgba(10,22,40,0.9) 60%, rgba(13,30,58,0.95) 100%),
                url('https://picsum.photos/id/1015/2000/1200') center/cover no-repeat;">

    <div class="absolute inset-0 bg-gradient-to-br from-blue-950/80 via-slate-900/85 to-slate-950/90"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 py-16 md:py-24 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            {{-- Left Content Column --}}
            <div class="lg:col-span-7 max-w-2xl">
                {{-- Live / Upcoming Badge --}}
                @if($liveLivestream)
                    <a href="{{ route('stream.view', $liveLivestream->id) }}"
                       class="inline-flex items-center gap-2 mb-6 px-5 py-2.5 rounded-full bg-red-500/10 border border-red-500/30 hover:bg-red-500/15 transition-colors">
                        <span class="live-badge">● LIVE</span>
                        <span class="text-sm text-red-200 font-medium">{{ $liveLivestream->title }}</span>
                        <span class="text-red-400 text-xs font-medium">→ Watch Now</span>
                    </a>
                @else
                    <div class="inline-flex items-center gap-2 mb-6 px-5 py-2.5 rounded-full bg-gold/10 border border-gold/30">
                        <span class="text-gold text-sm">📅 Event Date:</span>
                        <span class="text-sm text-gray-200">Saturday, 18th July 2026 · 5:00 PM GMT+1</span>
                    </div>
                @endif

                <h1 class="font-cinzel font-bold text-white leading-[1.1] tracking-tight mb-4"
                    style="font-size: clamp(2.25rem, 7vw, 4.25rem);">
                    A Day Of<br>
                    <span class="bg-gradient-to-r from-gold to-yellow-300 bg-clip-text text-transparent">Blessings</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-6 max-w-lg">
                    Welcome to our community. Join us for a special experience of The Word, Worship, Miracles, and Testimonies transforming lives globally.
                </p>

                {{-- Live Countdown Timer Frame --}}
                <div id="countdown-container" class="grid grid-cols-4 gap-3 max-w-sm mb-8 text-center">
                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 backdrop-blur-sm">
                        <span id="days" class="block text-2xl md:text-3xl font-bold text-gold">00</span>
                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-medium">Days</span>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 backdrop-blur-sm">
                        <span id="hours" class="block text-2xl md:text-3xl font-bold text-gold">00</span>
                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-medium">Hrs</span>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 backdrop-blur-sm">
                        <span id="minutes" class="block text-2xl md:text-3xl font-bold text-gold">00</span>
                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-medium">Mins</span>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 backdrop-blur-sm">
                        <span id="seconds" class="block text-2xl md:text-3xl font-bold text-red-400">00</span>
                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-medium">Secs</span>
                    </div>
                </div>

                {{-- Kept Registration Trigger Button --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="button" onclick="openRegistrationModal()"
                       class="btn-gold text-base sm:text-lg px-8 py-4 inline-flex justify-center items-center gap-2 shadow-lg shadow-gold/20 hover:scale-[1.02] transition-transform cursor-pointer">
                        🎟️ Register for A Day Of Blessings
                    </button>
                </div>
            </div>

            {{-- Right Graphic/Image Column (Maintained Exactly on the Right) --}}
            <div class="lg:col-span-5 w-full flex items-center justify-center">
                <div class="relative w-full max-w-md lg:max-w-none group">
                    <div class="absolute -inset-1.5 bg-gradient-to-r from-gold to-yellow-500 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-1000"></div>

                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gold/25 w-full bg-slate-950 p-1">
                        <img src="/images/adob.jpeg"
                             alt="A Day of Blessings Event Media Showcase"
                             class="w-full h-auto max-h-[500px] object-contain rounded-xl transform group-hover:scale-[1.01] transition-transform duration-500 ease-out brightness-95">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#0a1628] to-transparent"></div>
</section>


{{-- INTERACTIVE POPUP MODAL CONTAINING THE UPDATED FORM --}}
<div id="regModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-950/80 backdrop-blur-sm" onclick="closeRegistrationModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform rounded-2xl shadow-2xl border border-gold/30 bg-[#0e192e]">
            <div class="p-6 sm:p-8 relative">
                {{-- Close Button --}}
                <button onclick="closeRegistrationModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors text-2xl font-bold cursor-pointer">&times;</button>

                <div class="mb-5">
                    <h3 class="text-xl font-cinzel font-bold text-white mb-1">Register For A Day Of Blessings</h3>
                    <p class="text-xs text-gray-400">Secure your pass and submit your prayer requests to our global altar team.</p>
                </div>

                @if(session('success'))
                    <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #a7f3d0; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        <strong>🎉 Success:</strong> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('member.event.register') }}" method="POST" class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                            <strong>⚠️ Registration Failed:</strong>
                            <ul style="margin-top: 4px; padding-left: 20px;" class="text-xs list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Full Name</label>
                            <input type="text" name="full_name" required placeholder="John Doe"
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Email Address</label>
                            <input type="email" name="email" required placeholder="johndoe@example.com"
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Phone Number</label>
                            <input type="tel" name="phone" placeholder="+1 (555) 000-0000"
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                        </div>

                        {{-- Searchable Country Dropdown Option Container --}}
                        <div class="relative">
                            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Country</label>
                            <div class="relative">
                                <input type="text" id="countrySearch" placeholder="Type to search country..." autocomplete="off" required
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors cursor-pointer">
                                <span class="absolute right-3 top-3 text-gray-400 pointer-events-none text-xs">▼</span>
                            </div>
                            <input type="hidden" name="country" id="countryValue">

                            {{-- Dropdown Engine Menu Drawer --}}
                            <div id="countryDropdown" class="absolute left-0 right-0 z-50 hidden mt-1 max-h-40 overflow-y-auto bg-slate-950 border border-slate-700 rounded-xl shadow-2xl custom-scrollbar">
                                @php
                                    $countries = ['Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Mauritania', 'Mauritius', 'Mexico', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'];
                                @endphp
                                @foreach($countries as $country)
                                    <div class="country-option px-4 py-2 text-sm text-gray-300 hover:bg-gold/20 hover:text-white cursor-pointer transition-colors" data-value="{{ $country }}">
                                        {{ $country }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Prayer Request Textarea Input Box Component --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Do you have a prayer request?</label>
                        <textarea name="prayer_request" rows="3" placeholder="Write down your prayer details or special expectations here..."
                                  class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors resize-none"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-gold py-3.5 text-sm font-semibold inline-flex justify-center items-center gap-2 cursor-pointer shadow-lg shadow-gold/10">
                            Confirm Attendance & Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL INTERACTION AND TIMER SCRIPT RUNNERS --}}
<script>
    // Countdown Target: Saturday, July 18, 2026 17:00:00 GMT+0100
    const targetTime = new Date("July 18, 2026 17:00:00+01:00").getTime();

    function updateCountdownClock() {
        const now = new Date().getTime();
        const difference = targetTime - now;

        if (difference <= 0) {
            document.getElementById("countdown-container").innerHTML =
                `<div class="col-span-4 bg-red-500/10 border border-red-500/20 rounded-xl p-3 text-red-200 text-sm font-semibold tracking-wide animate-pulse uppercase">
                    ✨ The Broadcast is Live Now! ✨
                 </div>`;
            return;
        }

        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = String(days).padStart(2, '0');
        document.getElementById("hours").innerText = String(hours).padStart(2, '0');
        document.getElementById("minutes").innerText = String(minutes).padStart(2, '0');
        document.getElementById("seconds").innerText = String(seconds).padStart(2, '0');
    }

    updateCountdownClock();
    setInterval(updateCountdownClock, 1000);

    // Modal Visibility Mechanics
    function openRegistrationModal() {
        const modal = document.getElementById('regModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRegistrationModal() {
        const modal = document.getElementById('regModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Searchable country dropdown controller logic
    const searchInput = document.getElementById('countrySearch');
    const dropdownMenu = document.getElementById('countryDropdown');
    const hiddenInput = document.getElementById('countryValue');
    const options = document.querySelectorAll('.country-option');

    searchInput.addEventListener('focus', () => dropdownMenu.classList.remove('hidden'));

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    searchInput.addEventListener('input', () => {
        const filter = searchInput.value.toLowerCase();
        let elementsFound = 0;

        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            if(text.includes(filter)) {
                option.style.display = 'block';
                elementsFound++;
            } else {
                option.style.display = 'none';
            }
        });

        if(elementsFound === 0) {
            dropdownMenu.classList.add('hidden');
        } else {
            dropdownMenu.classList.remove('hidden');
        }
    });

    options.forEach(option => {
        option.addEventListener('click', () => {
            const selectedVal = option.getAttribute('data-value');
            searchInput.value = selectedVal;
            hiddenInput.value = selectedVal;
            dropdownMenu.classList.add('hidden');
        });
    });
</script>

<style>
    /* Thin custom styling scrollbar integration inside dropdown select drawer */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.6); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(201, 162, 39, 0.4); border-radius: 9999px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(201, 162, 39, 0.6); }
</style>

@endsection
