@extends('layouts.dashboard')

@section('title', 'AI Waste Segregation Guide')

@section('sidebar')
    <a href="{{ route('citizen.dashboard') }}"><i class="fa-solid fa-house"></i> Home</a>
    <a href="{{ route('citizen.segregation-guide') }}" class="active"><i class="fa-solid fa-robot"></i> AI Guide</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-5 text-center">
            <div class="mb-4">
                <i class="fa-solid fa-robot text-success fa-4x mb-3 animate-bounce"></i>
                <h2>Smart AI Segregation</h2>
                <p class="text-muted">Upload an image of your waste, and our AI will suggest the best disposal category and provide handling instructions.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success p-4 mb-4 text-start">
                    <h5 class="mb-2"><i class="fa-solid fa-check-circle"></i> AI Recommendation: <strong>{{ session('category') }}</strong></h5>
                    <p class="mb-0">{{ session('instructions') }}</p>
                </div>
            @endif

            <form action="{{ route('citizen.segregation-guide.process') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="upload-zone p-5 border-2 border-dashed rounded-lg mb-4 cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('wasteImage').click()">
                    <i class="fa-solid fa-cloud-arrow-up fa-3x text-muted mb-3"></i>
                    <p class="mb-0 text-muted">Click to upload or drag and drop waste image</p>
                    <input type="file" name="image" id="wasteImage" class="d-none" accept="image/*" onchange="previewImage(this)" required>
                </div>
                
                <div id="imagePreviewContainer" class="mb-4 d-none">
                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg">
                    <i class="fa-solid fa-magnifying-glass"></i> Analyze Waste Item
                </button>
            </form>
            
            <div class="mt-5 text-start">
                <h5 class="mb-3">Why segregate waste?</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <i class="fa-solid fa-recycle text-primary mb-2"></i>
                            <p class="small mb-0"><strong>Reduces Landfill:</strong> Proper segregation allows more items to be recycled.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <i class="fa-solid fa-hand-holding-heart text-danger mb-2"></i>
                            <p class="small mb-0"><strong>Safety:</strong> Prevents hazardous materials from entering the general waste stream.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<style>
    .upload-zone {
        border-style: dashed !important;
        border-width: 3px !important;
        border-color: #dee2e6 !important;
        transition: all 0.3s ease;
    }
    .upload-zone:hover {
        border-color: #2ecc71 !important;
        background-color: rgba(46, 204, 113, 0.05);
    }
    .dark .upload-zone {
        border-color: #4b5563 !important;
    }
    .dark .bg-light {
        background-color: #374151 !important;
        color: #f3f4f6;
    }
</style>
@endsection
