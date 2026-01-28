@extends('layouts.app')

@section('content')
<div class="bg-light py-5 mb-5">
    <div class="container py-4 text-center">
        <h1 class="fw-bold display-5 mb-3">Property Gallery</h1>
        <p class="text-muted lead mx-auto" style="max-width: 600px;">Explore our exclusive collection of luxury estates, urban apartments, and modern homes.</p>
    </div>
</div>

<div class="container my-5">
    <!-- Gallery Filters -->
    <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
        <a href="{{ route('gallery') }}" class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">All Properties</a>
        <a href="{{ route('gallery', ['type' => 'Sale']) }}" class="btn {{ request('type') === 'Sale' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">For Sale</a>
        <a href="{{ route('gallery', ['type' => 'Rent']) }}" class="btn {{ request('type') === 'Rent' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">For Rent</a>
    </div>

    @php
        $filteredProperties = $properties;
        if (request('type')) {
            $filteredProperties = $properties->where('type', request('type'));
        }
    @endphp

    @if($filteredProperties->isEmpty())
        <div class="text-center py-5">
            <div class="mb-4">
                <i data-lucide="search-x" class="size-16 text-muted opacity-25" style="width: 64px; height: 64px;"></i>
            </div>
            <h4 class="fw-bold text-dark">No properties found.</h4>
            <p class="text-muted">Try adjusting your filters or check back later.</p>
            <a href="{{ route('gallery') }}" class="btn btn-primary mt-3">View All Listings</a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($filteredProperties as $prop)
            @php
                $gallery = [];
                if ($prop->gallery_images) {
                    $decoded = json_decode($prop->gallery_images, true);
                    $gallery = is_array($decoded) ? $decoded : array_filter(explode(',', $prop->gallery_images));
                }
                $mainImage = $prop->image_url ?: (count($gallery) > 0 ? $gallery[0] : null);
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card property-card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="position-relative">
                        <img src="{{ $mainImage && Str::startsWith($mainImage, 'data:') ? $mainImage : ($mainImage ? asset(trim($mainImage)) : asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg')) }}"
                             class="card-img-top property-image"
                             alt="{{ $prop->title }}"
                             style="height: 260px;"
                             onerror="this.src='{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}'">

                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge {{ $prop->type === 'Sale' ? 'bg-success' : 'bg-primary' }} px-3 py-2 shadow-sm">
                                FOR {{ strtoupper($prop->type) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold text-dark mb-0">{{ $prop->title }}</h5>
                            <div class="text-primary fw-bold h5 mb-0">
                                ${{ number_format($prop->price) }}{!! $prop->type === 'Rent' ? '<small class="text-muted fw-normal" style="font-size: 0.7em;">/mo</small>' : '' !!}
                            </div>
                        </div>

                        <p class="text-muted small mb-4">
                            <i data-lucide="map-pin" class="size-3 me-1"></i>{{ $prop->address }}
                        </p>

                        <div class="d-flex justify-content-between py-3 border-top border-bottom mb-4">
                            <div class="text-center">
                                <div class="text-dark fw-semibold small"><i data-lucide="bed" class="size-4 me-1 text-primary"></i>{{ $prop->bedrooms }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Beds</div>
                            </div>
                            <div class="text-center border-start border-end px-3">
                                <div class="text-dark fw-semibold small"><i data-lucide="bath" class="size-4 me-1 text-primary"></i>{{ $prop->bathrooms }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Baths</div>
                            </div>
                            <div class="text-center">
                                <div class="text-dark fw-semibold small"><i data-lucide="maximize" class="size-4 me-1 text-primary"></i>{{ number_format($prop->sqft) }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Sqft</div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('properties.show', $prop) }}" class="btn btn-outline-primary rounded-pill py-2 fw-bold">
                                <i data-lucide="info" class="size-4 me-2"></i> View Full Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
