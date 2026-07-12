@extends('layouts.app')
@section('title', 'Login — OFCC')

{{-- Hide navbar and footer on the login page --}}
<style>
    nav, .navbar, header, #main-navbar, footer, .footer { display: none !important; }
</style>

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
             <div class="inline-flex items-center justify-center w-32 h-32 mb-4">
    <img src="/images/ofcc.png" alt="logo" class="w-2/2 h-2/2 object-contain">
</div>
            <h1 style="font-family:'Cinzel',serif; font-size:1.5rem; font-weight:700; color:white;">Welcome Back</h1>
            <p class="text-gray-400 text-sm mt-1">Sign in to your OFCC account</p>
        </div>

        <div class="rounded-2xl p-8" style="background:rgba(14,25,46,0.9); border:1px solid rgba(201,162,39,0.15);">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition"
                            style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2);"
                            placeholder="your@email.com">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition"
                            style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2);"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="w-full btn-gold text-center py-3 rounded-xl font-bold text-base">
                        Sign In
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-gold hover:underline font-medium">Create one free →</a>
            </p>
        </div>
    </div>
</div>
@endsection
