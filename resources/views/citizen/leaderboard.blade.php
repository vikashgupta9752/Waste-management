@extends('layouts.dashboard')

@section('title', 'Eco-Leaderboard')

@section('sidebar')
    <a href="{{ route('citizen.dashboard') }}"><i class="fa-solid fa-house"></i> Home</a>
    <a href="{{ route('citizen.leaderboard') }}" class="active"><i class="fa-solid fa-ranking-star"></i> Leaderboard</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- Top 3 Winners -->
        <div class="row mb-5 text-center align-items-end">
            @if(isset($topUsers[1]))
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm mb-3">
                    <div class="display-6 text-muted mb-2">2nd</div>
                    <div class="rounded-circle bg-secondary mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                        {{ substr($topUsers[1]->name, 0, 1) }}
                    </div>
                    <h5>{{ $topUsers[1]->name }}</h5>
                    <div class="text-success font-bold">{{ $topUsers[1]->points }} pts</div>
                </div>
            </div>
            @endif

            @if(isset($topUsers[0]))
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-lg mb-3" style="transform: scale(1.1); border-top: 5px solid #f1c40f !important;">
                    <i class="fa-solid fa-crown text-warning fa-3x mb-2"></i>
                    <div class="display-5 text-warning mb-2">1st</div>
                    <div class="rounded-circle bg-warning mx-auto mb-3 shadow" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                        {{ substr($topUsers[0]->name, 0, 1) }}
                    </div>
                    <h4>{{ $topUsers[0]->name }}</h4>
                    <div class="text-success h5 font-bold">{{ $topUsers[0]->points }} pts</div>
                </div>
            </div>
            @endif

            @if(isset($topUsers[2]))
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm mb-3">
                    <div class="display-6 text-muted mb-2">3rd</div>
                    <div class="rounded-circle bg-bronze mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; background-color: #cd7f32;">
                        {{ substr($topUsers[2]->name, 0, 1) }}
                    </div>
                    <h5>{{ $topUsers[2]->name }}</h5>
                    <div class="text-success font-bold">{{ $topUsers[2]->points }} pts</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Full List -->
        <div class="card p-4 border-0 shadow-sm">
            <h4 class="mb-4">Global Rankings</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th width="100">Rank</th>
                            <th>Citizen</th>
                            <th>Rank Title</th>
                            <th>CO2 Reduced</th>
                            <th class="text-end">Total Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topUsers as $index => $user)
                        <tr class="{{ $user->id == Auth::id() ? 'table-success border-success' : '' }}">
                            <td>
                                <span class="badge rounded-pill {{ $index < 3 ? 'bg-warning' : 'bg-secondary' }}">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->id == Auth::id() ? '(You)' : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-outline-primary border border-primary text-primary">{{ $user->rank }}</span>
                            </td>
                            <td>
                                <i class="fa-solid fa-cloud-arrow-down text-success me-1"></i> {{ $user->total_co2_saved }} kg
                            </td>
                            <td class="text-end fw-bold text-success">
                                {{ number_format($user->points) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .bg-outline-primary { background-color: transparent; }
    .dark .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    .dark .bg-light { background-color: #374151 !important; }
</style>
@endsection
