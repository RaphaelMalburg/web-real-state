@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container py-4 text-center">
        <h1 class="fw-bold display-4 mb-3">About EstatePro Agency</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">Redefining the real estate experience through innovation, integrity, and exceptional service since 2010.</p>
    </div>
</div>

<div class="container">
    <div class="row align-items-center g-5 mb-5 pb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4">Your Trusted Real Estate Partner</h2>
            <p class="text-muted mb-4 lead">We are a team of dedicated professionals committed to helping you find your perfect home.</p>
            <p class="text-muted mb-4">Founded in 2010, EstatePro Agency has grown to become one of the most trusted names in real estate. Our mission is to provide transparent, efficient, and personalized services to every client, whether they are buying, selling, or renting.</p>
            <p class="text-muted mb-5">Our expertise spans across luxury villas, urban apartments, and cozy countryside retreats. We believe that everyone deserves a place they can call home, and we work tirelessly to make that dream a reality.</p>

            <div class="row g-4 mb-5">
                <div class="col-6 col-md-4">
                    <div class="h2 fw-bold text-primary mb-0">15+</div>
                    <div class="text-muted small fw-bold text-uppercase">Years Experience</div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="h2 fw-bold text-primary mb-0">500+</div>
                    <div class="text-muted small fw-bold text-uppercase">Properties Sold</div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="h2 fw-bold text-primary mb-0">1k+</div>
                    <div class="text-muted small fw-bold text-uppercase">Happy Clients</div>
                </div>
            </div>

            <a href="{{ route('contact') }}" class="btn btn-primary btn-lg rounded-pill px-4">
                <i data-lucide="message-square" class="me-2 size-5"></i> Start a Conversation
            </a>
        </div>
        <div class="col-lg-6">
            <div class="position-relative">
                <div class="row g-3">
                    <div class="col-12">
                        <img src="{{ asset('assets/images/real-estate/elite-properties/agents/elite-agent-professional-1.jpg') }}" class="img-fluid rounded-4 shadow-lg w-100" alt="Our Team" style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="col-6">
                        <img src="{{ asset('assets/images/real-estate/urban-living/hero/urban-living-building-facade.jpg') }}" class="img-fluid rounded-4 shadow-sm w-100" alt="Our Office" style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="col-6">
                        <img src="{{ asset('assets/images/real-estate/elite-properties/agents/elite-agent-1.jpg') }}" class="img-fluid rounded-4 shadow-sm w-100" alt="Happy Clients" style="height: 200px; object-fit: cover;">
                    </div>
                </div>
                <!-- Experience Badge -->
                <div class="position-absolute top-50 start-0 translate-middle d-none d-md-block">
                    <div class="bg-white p-4 rounded-4 shadow-lg text-center" style="width: 150px;">
                        <div class="h1 fw-bold text-primary mb-0">#1</div>
                        <div class="small fw-bold text-dark text-uppercase">Agency 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Values -->
    <div class="py-5 bg-light rounded-5 p-5 mb-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Core Values</h2>
            <p class="text-muted">The principles that guide everything we do.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="bg-white rounded-circle p-4 shadow-sm d-inline-block mb-4">
                    <i data-lucide="shield-check" class="size-8 text-primary"></i>
                </div>
                <h4 class="fw-bold">Integrity</h4>
                <p class="text-muted small">We believe in complete transparency and honesty in every transaction and interaction.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white rounded-circle p-4 shadow-sm d-inline-block mb-4">
                    <i data-lucide="award" class="size-8 text-primary"></i>
                </div>
                <h4 class="fw-bold">Excellence</h4>
                <p class="text-muted small">We strive for the highest standards in service, property quality, and client satisfaction.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white rounded-circle p-4 shadow-sm d-inline-block mb-4">
                    <i data-lucide="users" class="size-8 text-primary"></i>
                </div>
                <h4 class="fw-bold">Client-First</h4>
                <p class="text-muted small">Your goals are our priority. We listen, adapt, and work tirelessly to exceed your expectations.</p>
            </div>
        </div>
    </div>
</div>
@endsection
