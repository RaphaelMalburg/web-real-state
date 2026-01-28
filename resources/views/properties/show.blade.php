@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gallery') }}">Properties</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $property->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Image Gallery -->
            <div id="propertyGallery" class="carousel slide shadow-sm rounded-4 overflow-hidden mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @php $hasMain = !empty($property->image_url); @endphp
                    @if($hasMain)
                        <div class="carousel-item active">
                            <img src="{{ Str::startsWith($property->image_url, 'data:') ? $property->image_url : asset($property->image_url) }}" class="d-block w-100 object-fit-cover" style="height: 500px;" alt="Main image">
                        </div>
                    @endif
                    @foreach($gallery as $index => $img)
                        <div class="carousel-item {{ !$hasMain && $index === 0 ? 'active' : '' }}">
                            <img src="{{ Str::startsWith($img, 'data:') ? $img : asset(trim($img)) }}" class="d-block w-100 object-fit-cover" style="height: 500px;" alt="Gallery image">
                        </div>
                    @endforeach
                    @if(!$hasMain && count($gallery) === 0)
                        <div class="carousel-item active">
                            <img src="{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}" class="d-block w-100 object-fit-cover" style="height: 500px;" alt="Placeholder">
                        </div>
                    @endif
                </div>
                @if(($hasMain && count($gallery) > 0) || count($gallery) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#propertyGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#propertyGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>

            <!-- Property Info -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge {{ $property->type === 'Sale' ? 'bg-success' : 'bg-primary' }} mb-2 px-3 py-2">FOR {{ strtoupper($property->type) }}</span>
                        <h1 class="fw-bold text-dark">{{ $property->title }}</h1>
                        <p class="text-muted"><i data-lucide="map-pin" class="size-4 me-1"></i> {{ $property->address }}</p>
                    </div>
                    <div class="text-end">
                        <div class="h2 fw-bold text-primary mb-0">${{ number_format($property->price) }}</div>
                        @if($property->type === 'Rent')
                            <small class="text-muted">per month</small>
                        @endif
                    </div>
                </div>

                <div class="row g-3 py-4 border-top border-bottom mb-4">
                    <div class="col-4 text-center border-end">
                        <div class="h4 fw-bold mb-0">{{ $property->bedrooms }}</div>
                        <div class="text-muted small">Bedrooms</div>
                    </div>
                    <div class="col-4 text-center border-end">
                        <div class="h4 fw-bold mb-0">{{ $property->bathrooms }}</div>
                        <div class="text-muted small">Bathrooms</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="h4 fw-bold mb-0">{{ number_format($property->sqft) }}</div>
                        <div class="text-muted small">Square Feet</div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3">Description</h5>
                <div class="text-muted lead mb-0" style="font-size: 1.1rem; line-height: 1.8;">
                    {{ $property->description }}
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Agent Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px; z-index: 5;">
                <h5 class="fw-bold mb-4">Inquire About This Property</h5>
                <form action="{{ route('inquiries.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Your Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="Your Email">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Message</label>
                        <textarea name="question" class="form-control" rows="4" required>I am interested in this property ({{ $property->title }}) and would like more information.</textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3 fw-bold">
                            <i data-lucide="send" class="size-4 me-2"></i> Send Inquiry
                        </button>
                        <button type="button" class="btn btn-outline-dark py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i data-lucide="calendar" class="size-4 me-2"></i> Schedule a Viewing
                        </button>
                    </div>
                </form>

                <div class="mt-4 pt-4 border-top">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('assets/images/real-estate/elite-properties/agents/elite-agent-1.jpg') }}" class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                        <div>
                            <div class="fw-bold">Elite Agent Team</div>
                            <div class="text-muted small">Specialist in {{ $property->type }}s</div>
                        </div>
                    </div>
                    <div class="text-muted small">
                        <p class="mb-1"><i data-lucide="phone" class="size-3 me-2"></i> +1 234 567 890</p>
                        <p class="mb-0"><i data-lucide="mail" class="size-3 me-2"></i> agents@estatepro.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suggested Properties -->
    @if($suggested->count() > 0)
        <div class="mt-5 pt-5">
            <h3 class="fw-bold mb-4">Similar Properties You Might Like</h3>
            <div class="row g-4">
                @foreach($suggested as $s_prop)
                    <div class="col-md-4">
                        <div class="card property-card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                            <img src="{{ $s_prop->image_url && Str::startsWith($s_prop->image_url, 'data:') ? $s_prop->image_url : ($s_prop->image_url ? asset($s_prop->image_url) : asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg')) }}" class="card-img-top" height="200" style="object-fit: cover;" alt="{{ $s_prop->title }}">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark text-truncate">{{ $s_prop->title }}</h6>
                                <div class="text-primary fw-bold mb-3">${{ number_format($s_prop->price) }}</div>
                                <a href="{{ route('properties.show', $s_prop) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">View Listing</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Schedule a Viewing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="property_id" value="{{ $property->id }}">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Select your preferred date and we'll confirm within 24 hours.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Your Name</label>
                        <input type="text" name="user_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Your Email</label>
                        <input type="email" name="user_email" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Preferred Date</label>
                        <input type="date" name="booking_date" class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Confirm Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
