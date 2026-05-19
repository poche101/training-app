@extends('layouts.member')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

<div class="max-w-lg">
    <div class="card mb-4">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full gold-gradient flex items-center justify-center text-navy font-bold text-xl flex-shrink-0">
                {{ strtoupper(substr($user->full_name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-bold text-white">{{ $user->full_name }}</h2>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block" style="background:rgba(201,162,39,0.1); color:#c9a227;">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('member.profile.update') }}">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                        style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2); border-radius:8px; padding:10px 12px; color:white; width:100%; font-size:0.875rem;">
                    @error('full_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">Email Address</label>
                    <input type="email" value="{{ $user->email }}" disabled
                        style="background:rgba(5,13,26,0.5); border:1px solid rgba(201,162,39,0.1); border-radius:8px; padding:10px 12px; color:#6b7280; width:100%; font-size:0.875rem;">
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        style="background:rgba(5,13,26,0.8); border:1px solid rgba(201,162,39,0.2); border-radius:8px; padding:10px 12px; color:white; width:100%; font-size:0.875rem;"
                        placeholder="+234 000 0000 000">
                </div>
                <button type="submit" class="btn-gold w-full py-2.5 text-center font-bold">Save Changes</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3 class="font-semibold text-white mb-3">Account Statistics</h3>
        <div class="grid grid-cols-2 gap-3">
            <div class="text-center p-3 rounded-lg" style="background:rgba(5,13,26,0.6);">
                <p class="text-xl font-bold text-gold">{{ $user->attendances()->count() }}</p>
                <p class="text-xs text-gray-400">Events Attended</p>
            </div>
            <div class="text-center p-3 rounded-lg" style="background:rgba(5,13,26,0.6);">
                <p class="text-xl font-bold text-gold">{{ $user->created_at->diffForHumans() }}</p>
                <p class="text-xs text-gray-400">Member Since</p>
            </div>
        </div>
    </div>
</div>

@endsection
