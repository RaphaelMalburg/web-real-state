@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-5">Property Gallery</h1>

    @if($properties->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3">
                <i data-lucide="home" class="size-16 text-muted text-opacity-25" style="width: 64px; height: 64px;"></i>
            </div>
            <h4 class="text-muted">No properties found.</h4>
            <p class="text-muted">Check back later for new listings.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($properties as $prop)
            <div class="col">
                <div class="card property-card h-100 shadow-sm border-0">
                    <div class="position-relative">
                        <img src="{{ Str::startsWith($prop->image_url, 'data:') ? $prop->image_url : asset($prop->image_url) }}"
                             class="card-img-top gallery-card-img"
                             alt="{{ $prop->title }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}';">
                        <span class="position-absolute top-0 end-0 m-3 badge {{ $prop->type === 'Sale' ? 'bg-success' : 'bg-info' }} rounded-pill">
                            {{ $prop->type }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-truncate">{{ $prop->title }}</h5>
                        <p class="text-primary fw-bold mb-2">
                            ${{ number_format($prop->price) }} {{ $prop->type === 'Rent' ? '/ mo' : '' }}
                        </p>
                        <p class="card-text text-muted small mb-3">
                            {{ Str::limit($prop->description, 80) }}
                        </p>
                        <div class="d-grid">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                View Details
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
