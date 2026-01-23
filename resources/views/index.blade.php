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

<!-- Properties Section -->
<div class="container my-5">
    <h2 class="text-center mb-4">Featured Properties</h2>
    <div class="row g-4">
        @foreach ($properties as $prop)
            @php 
                $gallery = [];
                if ($prop->gallery_images) {
                    $decoded = json_decode($prop->gallery_images, true);
                    $gallery = is_array($decoded) ? $decoded : array_filter(explode(',', $prop->gallery_images));
                }
                if ($prop->image_url) {
                    array_unshift($gallery, $prop->image_url);
                }
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card property-card h-100 shadow-sm">
                    <!-- Card Images (4 thumbnails) -->
                    <div class="p-2 bg-light">
                        <div class="row g-1">
                            @foreach (array_slice($gallery, 0, 4) as $img)
                                <div class="col-3">
                                    <img src="{{ Str::startsWith($img, 'data:') ? $img : asset(trim($img)) }}"
                                         class="img-fluid rounded"
                                         style="height: 60px; width: 100%; object-fit: cover;"
                                         alt="Gallery image"
                                         onerror="this.src='{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}'">
                                </div>
                            @endforeach
                            @if(count($gallery) < 4)
                                @for($i = 0; $i < (4 - count($gallery)); $i++)
                                    <div class="col-3">
                                        <img src="{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}"
                                             class="img-fluid rounded"
                                             style="height: 60px; width: 100%; object-fit: cover;"
                                             alt="Gallery image placeholder">
                                    </div>
                                @endfor
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="card-title mb-0">{{ $prop->title }}</h5>
                            <span class="badge {{ $prop->type === 'Sale' ? 'bg-success' : 'bg-info' }}">{{ $prop->type }}</span>
                        </div>
                        <p class="card-text text-primary fw-bold mb-1">${{ number_format($prop->price) }} {{ $prop->type === 'Rent' ? '/ month' : '' }}</p>
                        <p class="text-muted small mb-2"><i data-lucide="map-pin" class="size-3 me-1"></i>{{ $prop->address }}</p>

                        <div class="d-flex gap-3 mb-3 text-muted small">
                            <span><i data-lucide="bed" class="size-4 me-1"></i>{{ $prop->bedrooms }} Beds</span>
                            <span><i data-lucide="bath" class="size-4 me-1"></i>{{ $prop->bathrooms }} Baths</span>
                            <span><i data-lucide="maximize" class="size-4 me-1"></i>{{ $prop->sqft }} sqft</span>
                        </div>

                        <!-- Initial Text -->
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($prop->description, 80) }}
                        </p>

                        <!-- Expand/Collapse Info -->
                        <div class="collapse mb-3" id="details-{{ $prop->id }}">
                            <div class="card card-body bg-light border-0 small">
                                {{ $prop->description }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#details-{{ $prop->id }}">
                                <i data-lucide="info" class="size-4 me-1"></i> View Details
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal-{{ $prop->id }}">
                                <i data-lucide="calendar" class="size-4 me-1"></i> Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Modal -->
            <div class="modal fade" id="bookingModal-{{ $prop->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Book Viewing: {{ $prop->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $prop->id }}">
                            @php $isBookingError = (string) old('property_id') === (string) $prop->id; @endphp
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="user_name" class="form-control {{ $isBookingError && $errors->has('user_name') ? 'is-invalid' : '' }}" required placeholder="John Doe" value="{{ $isBookingError ? old('user_name') : '' }}">
                                    @if($isBookingError && $errors->has('user_name'))
                                        <div class="invalid-feedback">{{ $errors->first('user_name') }}</div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="user_email" class="form-control {{ $isBookingError && $errors->has('user_email') ? 'is-invalid' : '' }}" required placeholder="john@example.com" value="{{ $isBookingError ? old('user_email') : '' }}">
                                    @if($isBookingError && $errors->has('user_email'))
                                        <div class="invalid-feedback">{{ $errors->first('user_email') }}</div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Preferred Date</label>
                                    <input type="date" name="booking_date" class="form-control {{ $isBookingError && $errors->has('booking_date') ? 'is-invalid' : '' }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ $isBookingError ? old('booking_date') : '' }}">
                                    @if($isBookingError && $errors->has('booking_date'))
                                        <div class="invalid-feedback">{{ $errors->first('booking_date') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
