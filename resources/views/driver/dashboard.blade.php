@extends('layouts.dashboard')

@section('title', 'Driver Dashboard')

@section('sidebar')
    <a href="{{ route('driver.dashboard') }}" class="active"><i class="fa-solid fa-truck"></i> My Tasks</a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-primary-custom">
            <h3>{{ $tasksToday }}</h3>
            <p>Tasks Completed Today</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success-custom">
            <h3>{{ $efficiency }}%</h3>
            <p>Efficiency Rate</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-info-custom" style="background-color: #17a2b8; color: white; padding: 20px; border-radius: 10px;">
            <h3>Live</h3>
            <p>Location Sharing Active</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-dark text-white p-4 d-flex flex-row justify-content-between align-items-center" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #2c3e50, #000);">
            <div>
                <h4 class="mb-1"><i class="fa-solid fa-route me-2 text-info"></i> Smart Route Optimization</h4>
                <p class="mb-0 opacity-75">Click to calculate the most efficient path for all pending collections.</p>
            </div>
            <button class="btn btn-lg btn-info fw-bold text-white px-4 shadow" onclick="optimizeRoute()">
                <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Optimize Now
            </button>
        </div>
    </div>
</div>

<div class="card p-3">
    <h4>Assigned Tasks</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Req ID</th>
                <th>Citizen</th>
                <th>Category</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
            @php $req = $assignment->wasteRequest; @endphp
            <tr>
                <td>#{{ $req->id }}</td>
                <td>{{ $req->user->name }}</td>
                <td>{{ $req->wasteCategory->name }}</td>
                <td>
                    {{ $req->address }}
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ ($req->latitude && $req->longitude) ? $req->latitude . ',' . $req->longitude : urlencode($req->address) }}" target="_blank" class="btn btn-sm btn-outline-info ms-2">
                        <i class="fa-solid fa-location-arrow"></i> Route
                    </a>
                </td>
                <td>
                    <span class="badge bg-{{ $req->status == 'assigned' ? 'secondary' : ($req->status == 'on_the_way' ? 'info' : ($req->status == 'collected' ? 'warning' : 'success')) }}">
                        {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                    </span>
                </td>
                <td>
                    @if($req->status != 'disposed')
                    <form action="{{ route('driver.update-status') }}" method="POST" class="d-flex flex-column" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                        <div class="d-flex mb-2">
                            <select name="status" class="form-select form-select-sm me-2" required>
                                @if($req->status == 'assigned') <option value="on_the_way">Start Route</option> @endif
                                @if($req->status == 'on_the_way') <option value="collected">Collected</option> @endif
                                @if($req->status == 'collected') <option value="disposed">Disposed</option> @endif
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Update</button>
                        </div>
                        
                        @if($req->status == 'on_the_way')
                            <div class="small mb-1">Upload Proof:</div>
                            <input type="file" name="image_proof" class="form-control form-control-sm mb-2">
                        @endif
                    </form>
                    
                    @if(in_array($req->status, ['assigned', 'on_the_way']))
                        <button type="button" class="btn btn-sm btn-dark ms-2" onclick="openScanner({{ $req->id }})">
                            <i class="fa-solid fa-qrcode"></i> Scan QR
                        </button>
                    @endif
                    @else
                        <span class="text-success"><i class="fa-solid fa-check"></i> Disposed</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- QR Scan Modal -->
<div class="modal fade" id="scanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Waste QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="reader" style="width: 100%; border-radius: 10px; overflow: hidden;"></div>
                <div id="scan-status" class="mt-3 small text-muted">Scanning for QR code...</div>
                
                <hr>
                <p class="small">Manual Entry (if camera fails):</p>
                <div class="input-group">
                    <input type="text" id="manual-qr" class="form-control" placeholder="Enter QR Code">
                    <button class="btn btn-primary" onclick="verifyManualQR()">Verify</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;
    let activeRequestId = null;

    function openScanner(requestId) {
        activeRequestId = requestId;
        const modal = new bootstrap.Modal(document.getElementById('scanModal'));
        modal.show();

        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .catch(err => {
                console.error("Camera access failed", err);
                document.getElementById('scan-status').innerText = "Camera access failed. Use manual entry.";
            });
    }

    function onScanSuccess(decodedText, decodedResult) {
        html5QrCode.stop().then(() => {
            verifyQR(decodedText);
        });
    }

    function verifyManualQR() {
        const code = document.getElementById('manual-qr').value;
        if (code) verifyQR(code);
    }

    function verifyQR(code) {
        fetch('{{ route("driver.verify-qr") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                waste_request_id: activeRequestId,
                qr_code: code
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
                // Restart scanner if failed
                openScanner(activeRequestId);
            }
        });
    }

    // Stop scanner when modal closed
    document.getElementById('scanModal').addEventListener('hidden.bs.modal', function () {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop();
        }
    });

    // Live Location Sharing
    if (navigator.geolocation) {
        setInterval(() => {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                fetch('{{ route("driver.update-location") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                });
            });
        }, 10000); // Update every 10 seconds
    }

    function optimizeRoute() {
        if (!navigator.geolocation) {
            alert('Geolocation is required for route optimization.');
            return;
        }

        navigator.geolocation.getCurrentPosition((position) => {
            const origin = `${position.coords.latitude},${position.coords.longitude}`;
            const waypoints = [];
            
            @foreach($assignments as $assignment)
                @if($assignment->wasteRequest->status != 'disposed' && $assignment->wasteRequest->latitude)
                    waypoints.push('{{ $assignment->wasteRequest->latitude }},{{ $assignment->wasteRequest->longitude }}');
                @endif
            @endforeach

            if (waypoints.length === 0) {
                alert('No pending tasks with valid coordinates found.');
                return;
            }

            // We take the last waypoint as the destination and others as waypoints
            const destination = waypoints.pop();
            const pts = waypoints.join('|');

            const url = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}&waypoints=${pts}&travelmode=driving`;
            window.open(url, '_blank');
        }, (err) => {
            alert('Could not get your current location. Using task sequence instead.');
            // Fallback logic if needed
        });
    }
</script>
@endsection
