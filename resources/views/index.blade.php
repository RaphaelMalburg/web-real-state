@extends('layouts.app')

@section('content')
<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($carousel_items as $index => $item)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach ($carousel_items as $index => $item)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset($item['img']) }}"
                     class="d-block w-100"
                     alt="{{ $item['title'] }}"
                     onerror="this.src='{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}'">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Search & Filter Section -->
<div class="container mt-n5 position-relative z-index-10">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <form action="{{ route('home') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-bold small text-muted">Search Location</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i data-lucide="search" class="size-4 text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="City, neighborhood, or address" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label fw-bold small text-muted">Property Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="Sale" {{ request('type') === 'Sale' ? 'selected' : '' }}>For Sale</option>
                    <option value="Rent" {{ request('type') === 'Rent' ? 'selected' : '' }}>For Rent</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label fw-bold small text-muted">Price Range</label>
                <select name="price_range" class="form-select">
                    <option value="">Any Price</option>
                    <option value="0-500000" {{ request('price_range') === '0-500000' ? 'selected' : '' }}>Under $500k</option>
                    <option value="500000-1000000" {{ request('price_range') === '500000-1000000' ? 'selected' : '' }}>$500k - $1M</option>
                    <option value="1000000+" {{ request('price_range') === '1000000+' ? 'selected' : '' }}>$1M+</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-bold small text-muted">Min Bedrooms</label>
                <select name="bedrooms" class="form-select">
                    <option value="">Any</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+ Beds</option>
                    @endfor
                </select>
            </div>
            <div class="col-lg-2 col-md-6 d-grid">
                <button type="submit" class="btn btn-primary fw-bold">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Properties Section -->
<div class="container my-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Featured Properties</h2>
            <p class="text-muted small mb-0">Discover our hand-picked selection of premium listings</p>
        </div>
        @if(request()->anyFilled(['search', 'type', 'price_range', 'bedrooms']))
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Clear Filters</a>
        @endif
    </div>

    @if($properties->isEmpty())
        <div class="text-center py-5">
            <i data-lucide="search-x" class="size-12 text-muted mb-3"></i>
            <h4>No properties found</h4>
            <p class="text-muted">Try adjusting your search filters to find what you're looking for.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">View All Properties</a>
        </div>
    @else
        <div class="row g-4">
        @foreach ($properties as $prop)
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
                    <!-- Main Image with Overlay Badge -->
                    <div class="position-relative">
                        <img src="{{ $mainImage && Str::startsWith($mainImage, 'data:') ? $mainImage : ($mainImage ? asset(trim($mainImage)) : asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg')) }}"
                             class="card-img-top property-image"
                             alt="{{ $prop->title }}"
                             onerror="this.src='{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}'">

                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge {{ $prop->type === 'Sale' ? 'bg-success' : 'bg-primary' }} px-3 py-2 shadow-sm">
                                FOR {{ strtoupper($prop->type) }}
                            </span>
                        </div>

                        @if(count($gallery) > 0)
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 px-2 py-1 small">
                                    <i data-lucide="image" class="size-3 me-1"></i> {{ count($gallery) + ($prop->image_url ? 1 : 0) }} Photos
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold text-dark mb-0">{{ $prop->title }}</h5>
                            <div class="text-primary fw-bold h5 mb-0">
                                ${{ number_format($prop->price) }}{!! $prop->type === 'Rent' ? '<small class="text-muted fw-normal" style="font-size: 0.7em;">/mo</small>' : '' !!}
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <i data-lucide="map-pin" class="size-3 me-1"></i>{{ $prop->address }}
                        </p>

                        <div class="d-flex justify-content-between py-3 border-top border-bottom mb-3">
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

                        <p class="card-text text-muted small mb-4">
                            {{ Str::limit($prop->description, 90) }}
                        </p>

                        <div class="d-grid gap-2">
                            <a href="{{ route('properties.show', $prop) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i data-lucide="info" class="size-4 me-1"></i> View Details
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-primary btn-sm rounded-pill">
                                <i data-lucide="send" class="size-4 me-1"></i> Inquire Now
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
