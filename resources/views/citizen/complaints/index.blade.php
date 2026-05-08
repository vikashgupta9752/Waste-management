@extends('layouts.dashboard')

@section('title', 'My Complaints')

@section('sidebar')
    <a href="{{ route('citizen.dashboard') }}"><i class="fa-solid fa-house"></i> Home</a>
    <a href="{{ route('citizen.complaints') }}" class="active"><i class="fa-solid fa-circle-exclamation"></i> Complaints</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card p-4 shadow-sm border-0">
            <h4>Report an Issue</h4>
            <p class="text-muted small">Missed pickup? Illegal dumping? Report it here with a photo and location.</p>
            
            <form action="{{ route('citizen.complaints.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="e.g., Missed pickup on Main St" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Provide details about the issue..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Location Details</label>
                    <button type="button" id="getLocBtn" class="btn btn-outline-info btn-sm mb-2 w-100">
                        <i class="fa-solid fa-location-dot"></i> Tag My Location
                    </button>
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                    <div id="locStatus" class="small text-muted">Location optional but recommended.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Photo Evidence</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-danger w-100 shadow-sm">
                    <i class="fa-solid fa-paper-plane"></i> Submit Complaint
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card p-4 shadow-sm border-0">
            <h4>Complaint History</h4>
            <div class="table-responsive mt-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $complaint->subject }}</div>
                                @if($complaint->admin_comment)
                                    <div class="small text-info mt-1"><i class="fa-solid fa-comment-dots"></i> {{ $complaint->admin_comment }}</div>
                                @endif
                            </td>
                            <td>{{ $complaint->created_at->format('M d') }}</td>
                            <td>
                                <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'danger') }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No complaints reported yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('getLocBtn').addEventListener('click', function() {
        const btn = this;
        const status = document.getElementById('locStatus');
        
        if (!navigator.geolocation) {
            alert('Geolocation not supported');
            return;
        }

        btn.disabled = true;
        status.innerText = "Fetching coordinates...";

        navigator.geolocation.getCurrentPosition((pos) => {
            document.getElementById('lat').value = pos.coords.latitude;
            document.getElementById('lng').value = pos.coords.longitude;
            status.innerHTML = `<span class="text-success"><i class="fa-solid fa-check"></i> Geo-tagged successfully!</span>`;
            btn.classList.remove('btn-outline-info');
            btn.classList.add('btn-success');
        }, (err) => {
            status.innerText = "Error fetching location.";
            btn.disabled = false;
        });
    });
</script>
@endsection
