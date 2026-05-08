@extends('layouts.dashboard')

@section('title', 'Smart Bin Monitoring (IoT Simulation)')

@section('sidebar')
    <a href="{{ route('citizen.dashboard') }}"><i class="fa-solid fa-house"></i> Home</a>
    <a href="{{ route('citizen.bins') }}" class="active"><i class="fa-solid fa-trash-can"></i> Smart Bins</a>
@endsection

@section('content')
<div class="row g-4">
    @foreach($bins as $bin)
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ $bin->location_name }}</h5>
                    <span class="badge bg-{{ $bin->fill_level >= 90 ? 'danger' : ($bin->fill_level >= 50 ? 'warning' : 'success') }}">
                        {{ $bin->status }}
                    </span>
                </div>
                
                <div class="progress mb-3" style="height: 30px; border-radius: 15px; background: #eee;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $bin->fill_level >= 90 ? 'danger' : ($bin->fill_level >= 50 ? 'warning' : 'success') }}" 
                         role="progressbar" 
                         style="width: {{ $bin->fill_level }}%;" 
                         aria-valuenow="{{ $bin->fill_level }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $bin->fill_level }}%
                    </div>
                </div>

                <div class="small text-muted mb-4">
                    <i class="fa-solid fa-location-dot me-1"></i> {{ $bin->latitude }}, {{ $bin->longitude }}
                </div>

                <hr>
                
                <label class="form-label small fw-bold">Simulate Fill Level:</label>
                <div class="input-group">
                    <input type="range" class="form-range flex-grow-1" min="0" max="100" value="{{ $bin->fill_level }}" 
                           id="range-{{ $bin->id }}" oninput="updateRangeVal({{ $bin->id }}, this.value)">
                    <span class="ms-2 fw-bold" id="val-{{ $bin->id }}">{{ $bin->fill_level }}%</span>
                </div>
                <button class="btn btn-sm btn-outline-primary mt-3 w-100" onclick="simulateUpdate({{ $bin->id }})">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Sync to IoT Cloud
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($bins->isEmpty())
    <div class="alert alert-info text-center py-5">
        <i class="fa-solid fa-circle-info fa-3x mb-3 opacity-25"></i>
        <h4>No Smart Bins Registered</h4>
        <p>Contact admin to add smart bins to your area.</p>
    </div>
@endif

@endsection

@section('scripts')
<script>
    function updateRangeVal(id, val) {
        document.getElementById('val-' + id).innerText = val + '%';
    }

    async function simulateUpdate(id) {
        const val = document.getElementById('range-' + id).value;
        try {
            const response = await fetch(`/citizen/bins/${id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ fill_level: val })
            });
            
            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.message || 'Server error occurred');
            }

            const data = await response.json();
            if (data.success) {
                alert('IoT Data Synced! ' + (val >= 90 ? 'Pickup request automatically generated.' : ''));
                location.reload();
            }
        } catch (error) {
            console.error('IoT Sync Error:', error);
            alert('IoT Sync Failed: ' + error.message);
        }
    }
</script>
@endsection
