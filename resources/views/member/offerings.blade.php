@extends('layouts.member')
@section('title', 'Offerings & Partnership')
@section('page-title', 'Offerings & Partnership')

@section('content')

<div class="max-w-2xl">
    <p class="text-gray-400 text-sm mb-6 leading-relaxed">
        Your giving supports the global outreach mission of OFCC. Every contribution helps us reach more souls and expand the Kingdom.
    </p>

    @forelse($offerings as $offering)
    <div class="card mb-4">
        <h3 class="font-bold text-white text-lg mb-2">{{ $offering->title }}</h3>
        @if($offering->description)
            <p class="text-sm text-gray-400 mb-4 leading-relaxed">{{ $offering->description }}</p>
        @endif

        {{-- Bank Details --}}
        @if($offering->account_name || $offering->bank_name)
        <div class="rounded-xl p-4 mb-4" style="background:rgba(5,13,26,0.7); border:1px solid rgba(201,162,39,0.15);">
            <p class="text-xs text-gold font-semibold uppercase tracking-wider mb-3">Bank Transfer Details</p>
            <div class="space-y-2">
                @if($offering->account_name)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Account Name</span>
                        <span class="text-white font-medium">{{ $offering->account_name }}</span>
                    </div>
                @endif
                @if($offering->account_number)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Account Number</span>
                        <span class="text-white font-mono font-medium">{{ $offering->account_number }}</span>
                    </div>
                @endif
                @if($offering->bank_name)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Bank</span>
                        <span class="text-white font-medium">{{ $offering->bank_name }}</span>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Online Payment --}}
        @if($offering->payment_link)
        <a href="{{ $offering->payment_link }}" target="_blank" class="btn-gold inline-block">
            💳 Give Online
            @if($offering->payment_provider)
                via {{ ucfirst($offering->payment_provider) }}
            @endif
        </a>
        @endif
    </div>
    @empty
    <div class="card text-center py-16">
        <p class="text-4xl mb-4">💰</p>
        <p class="text-gray-400">Offering information will be available soon.</p>
    </div>
    @endforelse
</div>

@endsection
