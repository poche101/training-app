@extends('layouts.app')

@section('title', 'OFCC — Healing Streams Prayer Outreach')

{{-- Force override to ensure no navbar elements from layouts.app render visible --}}
<style>
    nav, .navbar, header, #main-navbar { display: none !important; }
</style>

@section('content')

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-93TD2CBN19"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-93TD2CBN19');
</script>

@php
    $liveLivestream = $liveLivestream ?? null;
@endphp

{{-- Hero Section — now just the stream/video player plus the registration button --}}
<section class="relative min-h-screen flex items-center overflow-hidden bg-slate-950 py-16 md:py-24">

    {{-- Decorative Background Gradients --}}
    <div class="absolute inset-0 bg-gradient-to-b from-[#0e192e]/30 via-slate-950 to-[#0a1628]"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 relative z-10 w-full">

        <h2 class="font-cinzel font-bold text-white text-xl sm:text-2xl mb-6 text-center">
            @if($liveLivestream)
                Pre Healing Streams<span class="bg-gradient-to-r from-gold to-yellow-300 bg-clip-text text-transparent">Prayer Outreach</span> Re-broadcast
            @else
                Watch Outreach <span class="bg-gradient-to-r from-gold to-yellow-300 bg-clip-text text-transparent">Rebroadcast</span>
            @endif
        </h2>

        {{-- Video Content Player Box Frame Container --}}
        <div class="rounded-2xl overflow-hidden flex flex-col" style="border:2px solid {{ $liveLivestream ? 'rgba(239,68,68,0.5)' : 'rgba(212,175,55,0.4)' }}; box-shadow: 0 0 40px rgba(14,25,46,0.5);">

            {{-- Player Top Control Bar --}}
            <div style="background: rgba(14,25,46,0.8); padding:14px 20px; display:flex; align-items:center; justify-content:space-between;" class="backdrop-blur-md">
                <div class="flex items-center gap-3">
                    @if($liveLivestream)
                        <span class="live-badge">● LIVE NOW</span>
                        <span class="text-white font-semibold text-sm sm:text-base">{{ $liveLivestream->title }}</span>
                        <span id="viewerCount" class="inline-flex items-center gap-1 text-xs text-gray-300 bg-white/5 border border-white/10 rounded-full px-2.5 py-1 whitespace-nowrap">
                            👀 <span id="viewerCountNumber">–</span> watching
                        </span>
                    @else
                        <span class="px-2.5 py-1 text-[11px] font-bold tracking-wider text-gold bg-gold/10 border border-gold/20 rounded-md uppercase">Intro Video</span>
                        <span class="text-gray-200 font-semibold text-sm sm:text-base">Prayer Outreach Stream Presentation</span>
                    @endif
                </div>

            </div>

            {{-- Player Screen Output Canvas Frame --}}
            <div style="aspect-ratio:16/9; background:#000;" class="flex-1 w-full relative">
                @if($liveLivestream)
                    @if($liveLivestream->embed_url)
                        <iframe src="{{ $liveLivestream->embed_url }}" style="width:100%; height:100%;" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                            <p style="color:#4a5568;">Stream starting soon...</p>
                        </div>
                    @endif
                @else
                    {{-- Shifted Background Video here to serve as the default showcase asset component --}}
                    <video id="heroBgVideo" autoplay muted loop playsinline webkit-playsinline="true"
                           controls disablePictureInPicture disableRemotePlayback preload="auto"
                           class="w-full h-full object-cover">
                        <source src="https://s3.eu-west-2.amazonaws.com/lodams-videoshare/videos/ofcc_601699fe3ccc7b0007cbc451.mp4" type="video/mp4">
                    </video>
                @endif
            </div>
        </div>

        {{-- Live Comments & Prayer Team Responses Panel — only shown
             while a stream is actually live, mirroring the "Participate
             Live" section above it. --}}
        @if($liveLivestream)
        <div id="commentsPanel" class="mt-6 rounded-2xl border border-white/10 bg-[#0e192e]/60 backdrop-blur-md overflow-hidden">
            <div style="padding:14px 20px; border-bottom:1px solid rgba(255,255,255,0.08);">
                <span class="text-white font-semibold text-sm">💬 Live Comments &amp;</span>
            </div>

            <div id="commentsList" class="p-4 sm:p-5 space-y-4 max-h-96 overflow-y-auto">
                <p style="color:#6b7280; font-size:13px;">Loading comments...</p>
            </div>

            <form id="commentForm" class="p-4 sm:p-5 border-t border-white/10 space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input type="text" id="commentName" placeholder="Your name" required maxlength="100"
                           class="sm:col-span-1 bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                    <input type="text" id="commentBody" placeholder="Share a comment or prayer request..." required maxlength="1000"
                           class="sm:col-span-2 bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                </div>
                <button type="submit" class="btn-gold text-sm px-6 py-2.5">Post Comment</button>
            </form>
        </div>
        @endif

        {{-- Registration Trigger Button --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
            <button type="button" id="openRegModalBtn"
               class="btn-gold text-base sm:text-lg px-8 py-4 inline-flex justify-center items-center gap-2 shadow-lg shadow-gold/20 hover:scale-[1.02] transition-transform cursor-pointer">
                🙏 Join Our outreach Fellowship Center
            </button>
        </div>

    </div>

    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#0a1628] to-transparent z-10"></div>
</section>

{{-- INTERACTIVE POPUP MODAL CONTAINING THE UPDATED FORM --}}
<div id="regModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div id="regModalBackdrop" class="fixed inset-0 transition-opacity bg-slate-950/80 backdrop-blur-sm"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform rounded-2xl shadow-2xl border border-gold/30 bg-[#0e192e]">
            <div class="p-6 sm:p-8 relative">
                {{-- Close Button --}}
                <button id="closeRegModalBtn" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors text-2xl font-bold cursor-pointer">&times;</button>

                @if(session('success'))
                    {{-- SUCCESS VIEW --}}
                    <div id="regSuccessView" class="text-center py-4">
                        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-3xl">
                            👍
                        </div>
                        <h3 class="text-xl font-cinzel font-bold text-white mb-2">You're Registered!</h3>
                        <p class="text-sm text-gray-300 mb-6 max-w-sm mx-auto leading-relaxed">
                            You have successfully registered for the Pre Healing Streams Prayer Outreach.
                            Get ready for a divine encounter of healing, deliverance, and restoration —
                            we'll see you there!
                        </p>

                        <div class="pt-5 border-t border-gold/10">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-4">
                                Share the registration link with your friends
                            </p>

                            {{-- Copyable Link Box --}}
                            <div class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 mb-4 max-w-sm mx-auto">
                                <span id="regShareLinkText" class="flex-1 text-sm text-gold font-medium truncate text-left">
                                    https://ethsch.org/4jH
                                </span>
                                <button type="button" onclick="copyRegistrationLink(this)"
                                        class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-800 hover:bg-gold/20 border border-slate-700 hover:border-gold/40 flex items-center justify-center text-white transition-colors"
                                        title="Copy link">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-center gap-3">
                                {{-- X (Twitter) — supports pre-filled share intent --}}
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode('Join me for the Pre Healing Streams Prayer Outreach 🙏') }}&url={{ urlencode('https://ethsch.org/4jH') }}"
                                   target="_blank" rel="noopener"
                                   class="w-11 h-11 rounded-full bg-slate-800 hover:bg-gold/20 border border-slate-700 hover:border-gold/40 flex items-center justify-center text-white transition-colors"
                                   title="Share on X">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>

                                {{-- Facebook — supports pre-filled share intent --}}
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode('https://ethsch.org/4jH') }}"
                                   target="_blank" rel="noopener"
                                   class="w-11 h-11 rounded-full bg-slate-800 hover:bg-gold/20 border border-slate-700 hover:border-gold/40 flex items-center justify-center text-white transition-colors"
                                   title="Share on Facebook">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                        <path d="M22 12.06C22 6.507 17.523 2 12 2S2 6.507 2 12.06c0 5.02 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.522 1.492-3.915 3.777-3.915 1.094 0 2.238.197 2.238.197v2.476h-1.26c-1.243 0-1.63.775-1.63 1.57v1.887h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.92 8.437-9.94z"/>
                                    </svg>
                                </a>

                                {{-- Instagram — no share-intent API; copies link + opens Instagram --}}
                                <a href="https://www.instagram.com/" target="_blank" rel="noopener"
                                   onclick="copyRegistrationLink(this)"
                                   class="w-11 h-11 rounded-full bg-slate-800 hover:bg-gold/20 border border-slate-700 hover:border-gold/40 flex items-center justify-center text-white transition-colors"
                                   title="Copy link & open Instagram">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                        <path d="M12 2c-2.717 0-3.056.012-4.123.06-1.064.049-1.79.218-2.427.465a4.902 4.902 0 0 0-1.772 1.153A4.902 4.902 0 0 0 2.525 5.45c-.247.637-.416 1.363-.465 2.427C2.012 8.944 2 9.283 2 12s.012 3.056.06 4.123c.049 1.064.218 1.79.465 2.427a4.902 4.902 0 0 0 1.153 1.772 4.902 4.902 0 0 0 1.772 1.153c.637.247 1.363.416 2.427.465C8.944 21.988 9.283 22 12 22s3.056-.012 4.123-.06c1.064-.049 1.79-.218 2.427-.465a4.902 4.902 0 0 0 1.772-1.153 4.902 4.902 0 0 0 1.153-1.772c.247-.637.416-1.363.465-2.427.048-1.067.06-1.406.06-4.123s-.012-3.056-.06-4.123c-.049-1.064-.218-1.79-.465-2.427a4.902 4.902 0 0 0-1.153-1.772A4.902 4.902 0 0 0 18.55 2.525c-.637-.247-1.363-.416-2.427-.465C15.056 2.012 14.717 2 12 2zm0 1.802c2.67 0 2.987.01 4.042.058.976.045 1.505.207 1.858.344.467.182.8.399 1.15.748.35.35.566.683.748 1.15.137.353.3.882.344 1.858.048 1.055.058 1.372.058 4.042s-.01 2.987-.058 4.042c-.045.976-.207 1.505-.344 1.858a3.1 3.1 0 0 1-.748 1.15 3.1 3.1 0 0 1-1.15.748c-.353.137-.882.3-1.858.344-1.055.048-1.372.058-4.042.058s-2.987-.01-4.042-.058c-.976-.045-1.505-.207-1.858-.344a3.1 3.1 0 0 1-1.15-.748 3.1 3.1 0 0 1-.748-1.15c-.137-.353-.3-.882-.344-1.858-.048-1.055-.058-1.372-.058-4.042s.01-2.987.058-4.042c.045-.976.207-1.505.344-1.858.182-.467.399-.8.748-1.15.35-.35.683-.566 1.15-.748.353-.137.882-.3 1.858-.344C9.013 3.812 9.33 3.802 12 3.802zm0 3.064a5.134 5.134 0 1 0 0 10.268A5.134 5.134 0 0 0 12 6.866zm0 8.468a3.334 3.334 0 1 1 0-6.668 3.334 3.334 0 0 1 0 6.668zm5.338-8.671a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/>
                                    </svg>
                                </a>

                                {{-- TikTok — no share-intent API; copies link + opens TikTok --}}
                                <a href="https://www.tiktok.com/" target="_blank" rel="noopener"
                                   onclick="copyRegistrationLink(this)"
                                   class="w-11 h-11 rounded-full bg-slate-800 hover:bg-gold/20 border border-slate-700 hover:border-gold/40 flex items-center justify-center text-white transition-colors"
                                   title="Copy link & open TikTok">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                        <path d="M16.6 5.82c-.9-.98-1.4-2.26-1.4-3.57h-3.14v13.9a3.2 3.2 0 1 1-3.2-3.2c.28 0 .55.03.81.09V9.86a6.35 6.35 0 0 0-.81-.05A6.36 6.36 0 1 0 15.46 16v-6.65a8.16 8.16 0 0 0 4.77 1.53V7.75c-1.29 0-2.48-.44-3.63-1.93z"/>
                                    </svg>
                                </a>
                            </div>
                            <p id="copyFeedback" class="text-xs text-emerald-400 mt-3 hidden">Link copied — paste it into your post!</p>
                        </div>

                        <button id="regModalDoneBtn" class="mt-6 btn-gold px-8 py-3 text-sm inline-flex items-center gap-2">
                            Done
                        </button>
                    </div>
                @else
                    {{-- REGISTRATION FORM VIEW --}}
                    <div id="regFormView">
                        <div class="mb-5">
                            <h3 class="text-xl font-cinzel font-bold text-white mb-1">Register For Pre Healing Streams Prayer Outreach</h3>
                            <p class="text-xs text-gray-400">Reserve your seat and send your healing or prayer request to our global altar team.</p>
                        </div>

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
                                           value="{{ old('full_name') }}"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Email Address</label>
                                    <input type="email" name="email" required placeholder="johndoe@example.com"
                                           value="{{ old('email') }}"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Phone Number</label>
                                    <input type="tel" name="phone" placeholder="+1 (555) 000-0000"
                                           value="{{ old('phone') }}"
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors">
                                </div>

                                {{-- Searchable Country Dropdown Option Container --}}
                                <div class="relative">
                                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Country</label>
                                    <div class="relative">
                                        <input type="text" id="countrySearch" placeholder="Type to search country..." autocomplete="off" required
                                               value="{{ old('country') }}"
                                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors cursor-pointer">
                                        <span class="absolute right-3 top-3 text-gray-400 pointer-events-none text-xs">▼</span>
                                    </div>
                                    <input type="hidden" name="country" id="countryValue" value="{{ old('country') }}">

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
                                <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Send us your prayer request.</label>
                                <textarea name="prayer_request" rows="3" placeholder="Send your prayer request here..."
                                          class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-gold transition-colors resize-none">{{ old('prayer_request') }}</textarea>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full btn-gold py-3.5 text-sm font-semibold inline-flex justify-center items-center gap-2 cursor-pointer shadow-lg shadow-gold/10">
                                    Confirm My Seat & Submit Prayer Request
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL INTERACTION AND VIDEO SCRIPT RUNNERS --}}
<script>
    // Video Engine Controller Framework setup
    const heroVideo = document.getElementById('heroBgVideo');
    if (heroVideo) {
        heroVideo.muted = true;
        heroVideo.defaultMuted = true;

        let playAttempted = false;
        const tryPlay = () => {
            if (playAttempted || !heroVideo.paused) return;
            playAttempted = true;
            const playPromise = heroVideo.play();
            if (playPromise !== undefined) {
                playPromise.catch(() => {
                    playAttempted = false;
                    const resumeOnInteraction = () => {
                        heroVideo.play().catch(() => {});
                        document.removeEventListener('click', resumeOnInteraction);
                        document.removeEventListener('touchstart', resumeOnInteraction);
                    };
                    document.addEventListener('click', resumeOnInteraction, { once: true });
                    document.addEventListener('touchstart', resumeOnInteraction, { once: true });
                });
            }
        };

        if (heroVideo.readyState >= 2) {
            tryPlay();
        } else {
            heroVideo.addEventListener('loadeddata', tryPlay, { once: true });
        }

        heroVideo.addEventListener('error', () => {
            heroVideo.style.display = 'none';
        });
    }

    // Modal Visibility Mechanics
    function openRegistrationModal() {
        const modal = document.getElementById('regModal');
        if (!modal) {
            console.warn('openRegistrationModal: #regModal not found in DOM');
            return;
        }
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRegistrationModal() {
        const modal = document.getElementById('regModal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Wire up modal triggers with addEventListener instead of inline onclick —
    // this avoids silent failures under a strict Content-Security-Policy
    // (which blocks inline event-handler attributes) and avoids any risk of
    // ID collisions with other onclick="..." markup elsewhere on the site.
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openRegModalBtn');
        if (openBtn) {
            openBtn.addEventListener('click', openRegistrationModal);
        } else {
            console.warn('openRegModalBtn not found — registration button will not open the modal');
        }

        const closeBtn = document.getElementById('closeRegModalBtn');
        if (closeBtn) closeBtn.addEventListener('click', closeRegistrationModal);

        const backdrop = document.getElementById('regModalBackdrop');
        if (backdrop) backdrop.addEventListener('click', closeRegistrationModal);

        const doneBtn = document.getElementById('regModalDoneBtn');
        if (doneBtn) doneBtn.addEventListener('click', closeRegistrationModal);
    });

    // Auto-open the registration modal if there's a success message or validation errors to show
    @if(session('success') || $errors->any())
        document.addEventListener('DOMContentLoaded', () => openRegistrationModal());
    @endif

    // Copy registration link to clipboard
    function copyRegistrationLink(btn) {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            const feedback = document.getElementById('copyFeedback');
            feedback.classList.remove('hidden');
            setTimeout(() => feedback.classList.add('hidden'), 2000);
        });
    }

    // Searchable country dropdown controller logic
    const searchInput = document.getElementById('countrySearch');
    const dropdownMenu = document.getElementById('countryDropdown');
    const hiddenInput = document.getElementById('countryValue');
    const options = document.querySelectorAll('.country-option');

    if (searchInput) {
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
    }

    // Live Viewer Count Engine — heartbeats the server every 10s while this
    // livestream is active, and updates the "👀 N watching" badge.
    @if($liveLivestream)
    (function () {
        const streamId = {{ $liveLivestream->id }};
        const heartbeatUrl = "{{ route('stream.heartbeat', $liveLivestream->id) }}";
        const csrfToken = "{{ csrf_token() }}";
        const countEl = document.getElementById('viewerCountNumber');
        let heartbeatTimer = null;

        async function sendHeartbeat() {
            try {
                const res = await fetch(heartbeatUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });
                if (!res.ok) return;
                const data = await res.json();
                if (countEl && typeof data.count !== 'undefined') {
                    countEl.innerText = data.count;
                }
            } catch (e) {
                // Silent fail — don't disrupt the viewing experience over a
                // missed heartbeat, the count will just be briefly stale.
                console.error('Viewer heartbeat failed', e);
            }
        }

        function startHeartbeats() {
            if (heartbeatTimer) return;
            sendHeartbeat();
            heartbeatTimer = setInterval(sendHeartbeat, 10000);
        }

        function stopHeartbeats() {
            if (!heartbeatTimer) return;
            clearInterval(heartbeatTimer);
            heartbeatTimer = null;
        }

        // Pause heartbeats when the tab is hidden so backgrounded viewers
        // age out of the count instead of inflating it indefinitely.
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopHeartbeats();
            } else {
                startHeartbeats();
            }
        });

        startHeartbeats();
    })();
    @endif

    // Live Comments Engine — posts a guest comment and polls for updates
    // (new comments + admin/pastoral team replies) every 10 seconds while
    // this livestream is active.
    @if($liveLivestream)
    (function () {
        const listUrl  = "{{ route('stream.comments', $liveLivestream->id) }}";
        const storeUrl = "{{ route('stream.comments.store', $liveLivestream->id) }}";
        const csrfToken = "{{ csrf_token() }}";
        const listEl = document.getElementById('commentsList');
        const form = document.getElementById('commentForm');
        const nameInput = document.getElementById('commentName');
        const bodyInput = document.getElementById('commentBody');
        let commentsTimer = null;

        // Remember the commenter's name across the session for convenience
        const savedName = sessionStorage.getItem('ofcc_commenter_name');
        if (savedName) nameInput.value = savedName;

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        function renderComments(comments) {
            if (!comments || !comments.length) {
                listEl.innerHTML = '<p style="color:#6b7280; font-size:13px;">No comments yet — be the first to share.</p>';
                return;
            }
            listEl.innerHTML = comments.map(c => `
                <div>
                    <div style="display:flex; gap:8px; align-items:baseline;">
                        <span style="color:#c9a227; font-weight:600; font-size:13px;">${escapeHtml(c.name || 'Guest')}</span>
                    </div>
                    <p style="color:#d1d5db; font-size:13px; margin-top:2px;">${escapeHtml(c.body)}</p>
                    ${(c.replies || []).map(r => `
                        <div style="margin-left:18px; margin-top:8px; padding:8px 12px; background:rgba(212,175,55,0.08); border-left:2px solid #c9a227; border-radius:6px;">
                            <span style="color:#fbbf24; font-weight:600; font-size:12px;">🙏 ${escapeHtml(r.name || 'Prayer Team')}</span>
                            <p style="color:#e5e7eb; font-size:13px; margin-top:2px;">${escapeHtml(r.body)}</p>
                        </div>
                    `).join('')}
                </div>
            `).join('<hr style="border-color:rgba(255,255,255,0.06); margin:14px 0;">');
        }

        async function pollComments() {
            try {
                const res = await fetch(listUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                renderComments(data.comments);
            } catch (e) {
                console.error('Comment poll failed', e);
            }
        }

        function startPolling() {
            if (commentsTimer) return;
            pollComments();
            commentsTimer = setInterval(pollComments, 10000);
        }

        function stopPolling() {
            if (!commentsTimer) return;
            clearInterval(commentsTimer);
            commentsTimer = null;
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopPolling();
            } else {
                startPolling();
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = nameInput.value.trim();
            const body = bodyInput.value.trim();
            if (!name || !body) return;

            sessionStorage.setItem('ofcc_commenter_name', name);

            try {
                const res = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name, body }),
                });
                if (res.ok) {
                    bodyInput.value = '';
                    pollComments();
                }
            } catch (e) {
                console.error('Post comment failed', e);
            }
        });

        startPolling();
    })();
    @endif
</script>

<style>
    /* Thin custom styling scrollbar integration inside dropdown select drawer */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.6); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(201, 162, 39, 0.4); border-radius: 9999px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(201, 162, 39, 0.6); }
</style>

@endsection
