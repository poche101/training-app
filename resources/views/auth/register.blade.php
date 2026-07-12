@extends('layouts.app')
@section('title', 'Register — OFCC')

{{-- Hide navbar and footer on the register page --}}
<style>
    nav, .navbar, header, #main-navbar, footer, .footer { display: none !important; }
</style>

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
         <div class="inline-flex items-center justify-center w-32 h-32 mb-4">
    <img src="/images/ofcc.png" alt="logo" class="w-2/2 h-2/2 object-contain">
</div>
            <h1 style="font-family:'Cinzel',serif; font-size:1.5rem; font-weight:700; color:white;">Join OFCC</h1>
            <p class="text-gray-400 text-sm mt-1">Create your free member account</p>
        </div>

        <div class="rounded-2xl p-8" style="background:rgba(14,25,46,0.9); border:1px solid rgba(201,162,39,0.15);">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2);"
                            placeholder="John Doe">
                        @error('full_name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2);"
                            placeholder="your@email.com">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number <span class="text-gray-500">(optional)</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2);"
                            placeholder="+234 000 0000 000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2);"
                            placeholder="Min. 8 characters">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2);"
                            placeholder="Repeat password">
                    </div>

                    <button type="submit" class="w-full btn-gold text-center py-3 rounded-xl font-bold text-base">
                        Create Account
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-gold hover:underline font-medium">Sign in →</a>
            </p>
        </div>
    </div>
</div>
@endsection
