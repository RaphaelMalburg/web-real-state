@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <h1 class="mb-4">About EstatePro Agency</h1>
            <p class="lead">We are a team of dedicated professionals committed to helping you find your perfect home.</p>
            <p>Founded in 2010, EstatePro Agency has grown to become one of the most trusted names in real estate. Our mission is to provide transparent, efficient, and personalized services to every client, whether they are buying, selling, or renting.</p>
            <p>Our expertise spans across luxury villas, urban apartments, and cozy countryside retreats. We believe that everyone deserves a place they can call home, and we work tirelessly to make that dream a reality.</p>

            <a href="{{ route('contact') }}" class="btn btn-primary btn-lg mt-3">
                <i data-lucide="message-square" class="me-2"></i> Contact Us
            </a>
        </div>
        <div class="col-md-6">
            <div class="row g-3">
                <div class="col-12">
                    <!-- Main Team Image -->
                    <img src="{{ asset('assets/images/real-estate/elite-properties/agents/elite-agent-professional-1.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="Our Team" style="height: 300px; object-fit: cover;">
                </div>
                <div class="col-6">
                    <!-- Office/Property Image -->
                    <img src="{{ asset('assets/images/real-estate/urban-living/hero/urban-living-building-facade.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="Our Office" style="height: 200px; object-fit: cover;">
                </div>
                <div class="col-6">
                    <!-- Another Agent/Client Image -->
                    <img src="{{ asset('assets/images/real-estate/elite-properties/agents/elite-agent-1.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="Happy Clients" style="height: 200px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
