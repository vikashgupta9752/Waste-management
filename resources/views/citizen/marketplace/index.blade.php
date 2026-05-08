@extends('layouts.dashboard')

@section('title', 'Recycling Marketplace')

@section('sidebar')
    <a href="{{ route('citizen.dashboard') }}"><i class="fa-solid fa-house"></i> Home</a>
    <a href="{{ route('citizen.marketplace') }}" class="active"><i class="fa-solid fa-store"></i> Marketplace</a>
@endsection

@section('content')
<div class="row">
    <!-- Marketplace Items -->
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Recyclables for Sale</h4>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Filter Category
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-menu-item px-3 py-1 text-decoration-none" href="#">All</a></li>
                    <li><a class="dropdown-menu-item px-3 py-1 text-decoration-none" href="#">Plastic</a></li>
                    <li><a class="dropdown-menu-item px-3 py-1 text-decoration-none" href="#">Paper</a></li>
                    <li><a class="dropdown-menu-item px-3 py-1 text-decoration-none" href="#">Metal</a></li>
                </ul>
            </div>
        </div>

        <div class="row g-4">
            @forelse($items as $item)
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm overflow-hidden marketplace-card">
                    <div class="position-relative">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa-solid fa-box-open fa-3x text-muted opacity-25"></i>
                            </div>
                        @endif
                        <span class="badge bg-success position-absolute top-0 end-0 m-3 shadow">
                            ${{ number_format($item->price, 2) }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-uppercase small fw-bold text-success">{{ $item->category }}</span>
                            <span class="small text-muted"><i class="fa-solid fa-user-tag"></i> {{ $item->user->name }}</span>
                        </div>
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text text-muted small">{{ $item->description }}</p>
                        <button class="btn btn-primary w-100 mt-2">
                            <i class="fa-solid fa-cart-plus"></i> Contact Seller
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-store-slash fa-4x text-muted mb-3 opacity-25"></i>
                <h5>No items available currently.</h5>
                <p class="text-muted">Be the first to list something for sale!</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Sell Form Sidebar -->
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm sticky-top" style="top: 20px;">
            <h4 class="mb-3">Sell Recyclables</h4>
            <p class="text-muted small">Turn your waste into wealth. List your sorted recyclables here for others to buy.</p>
            
            <form action="{{ route('citizen.marketplace.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Item Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., 5kg Clean Plastic Bottles" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="Plastic">Plastic</option>
                        <option value="Paper">Paper</option>
                        <option value="Metal">Metal</option>
                        <option value="E-Waste">E-Waste</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Asking Price ($)</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tell buyers about the quantity and quality..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 shadow-sm">
                    <i class="fa-solid fa-tag"></i> List Item Now
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .marketplace-card {
        transition: transform 0.3s ease, shadow 0.3s ease;
    }
    .marketplace-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .dark .bg-light { background-color: #374151 !important; }
</style>
@endsection
