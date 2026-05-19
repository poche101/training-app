@extends('layouts.admin')
@section('title', 'Analytics')
@section('page-title', 'Analytics & Insights')

@section('content')

{{-- Monthly Chart --}}
<div class="admin-card" style="padding:24px; margin-bottom:20px;">
    <h3 style="font-size:14px; font-weight:600; color:white; margin-bottom:20px;">Platform Growth — Last 6 Months</h3>

    {{-- Simple bar chart using CSS --}}
    @php
        $maxUsers = max(array_column($monthlyStats, 'users')) ?: 1;
        $maxDl    = max(array_column($monthlyStats, 'downloads')) ?: 1;
    @endphp

    <div style="display:flex; gap:4px; align-items:flex-end; height:160px; margin-bottom:10px;">
        @foreach($monthlyStats as $stat)
        <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%;">
            <div style="flex:1; display:flex; align-items:flex-end; gap:3px; width:100%;">
                <div style="flex:1; background:linear-gradient(180deg,#c9a227,rgba(201,162,39,0.4)); border-radius:4px 4px 0 0; height:{{ max(4, ($stat['users']/$maxUsers)*100) }}%; transition:height 0.3s;" title="Users: {{ $stat['users'] }}"></div>
                <div style="flex:1; background:linear-gradient(180deg,#60a5fa,rgba(96,165,250,0.4)); border-radius:4px 4px 0 0; height:{{ max(4, ($stat['downloads']/$maxDl)*100) }}%; transition:height 0.3s;" title="Downloads: {{ $stat['downloads'] }}"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="display:flex; gap:4px;">
        @foreach($monthlyStats as $stat)
        <div style="flex:1; text-align:center;">
            <p style="font-size:10px; color:#6b7280;">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div style="display:flex; gap:16px; margin-top:14px; padding-top:14px; border-top:1px solid rgba(201,162,39,0.08);">
        <div style="display:flex; align-items:center; gap:6px;">
            <div style="width:12px; height:12px; border-radius:3px; background:#c9a227;"></div>
            <span style="font-size:12px; color:#9ca3af;">New Members</span>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <div style="width:12px; height:12px; border-radius:3px; background:#60a5fa;"></div>
            <span style="font-size:12px; color:#9ca3af;">Downloads</span>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- Top Resources --}}
    <div class="admin-card" style="overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid rgba(201,162,39,0.08);">
            <h3 style="font-size:14px; font-weight:600; color:white;">Top Downloaded Resources</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resource</th>
                    <th>Type</th>
                    <th>Downloads</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topResources as $i => $res)
                <tr>
                    <td style="color:#c9a227; font-weight:700;">{{ $i + 1 }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span>{{ $res->file_icon }}</span>
                            <span style="color:white; font-size:13px;">{{ Str::limit($res->title, 30) }}</span>
                        </div>
                    </td>
                    <td style="font-size:11px; text-transform:uppercase; color:#c9a227; font-weight:600;">{{ $res->file_type }}</td>
                    <td style="font-weight:700; color:#34d399;">{{ number_format($res->download_count) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:28px; color:#4a5568;">No download data yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Event Attendance --}}
    <div class="admin-card" style="overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid rgba(201,162,39,0.08);">
            <h3 style="font-size:14px; font-weight:600; color:white;">Event Attendance</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Check-ins</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eventAttendance as $att)
                <tr>
                    <td style="color:white; font-size:13px;">{{ $att->event?->title ?? 'Unknown' }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="flex:1; height:6px; background:rgba(201,162,39,0.1); border-radius:3px; overflow:hidden;">
                                @php $maxAtt = $eventAttendance->max('count') ?: 1; @endphp
                                <div style="width:{{ ($att->count/$maxAtt)*100 }}%; height:100%; background:linear-gradient(90deg,#c9a227,#f0c84a); border-radius:3px;"></div>
                            </div>
                            <span style="color:#c9a227; font-weight:700; font-size:13px; min-width:28px; text-align:right;">{{ $att->count }}</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align:center; padding:28px; color:#4a5568;">No event data yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
