@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark">Get in Touch</h1>
        <p class="text-muted lead">Have questions about a property or our services? We're here to help you every step of the way.</p>
    </div>

    <div class="row g-5">
        <!-- Contact Information -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-primary text-white">
                <h3 class="fw-bold mb-4">Contact Information</h3>
                <p class="mb-5 opacity-75">Our team is available 7 days a week to ensure you find your perfect home.</p>

                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i data-lucide="phone" class="size-6 text-white"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Call Us</div>
                        <div class="fw-bold">+1 234 567 890</div>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i data-lucide="mail" class="size-6 text-white"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Email Us</div>
                        <div class="fw-bold">info@estatepro.com</div>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-5">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i data-lucide="map-pin" class="size-6 text-white"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Visit Our Office</div>
                        <div class="fw-bold">123 Real Estate St, City, Country</div>
                    </div>
                </div>

                <div class="mt-auto">
                    <h5 class="fw-bold mb-3">Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white opacity-75 hover-opacity-100"><i data-lucide="facebook" class="size-5"></i></a>
                        <a href="#" class="text-white opacity-75 hover-opacity-100"><i data-lucide="twitter" class="size-5"></i></a>
                        <a href="#" class="text-white opacity-75 hover-opacity-100"><i data-lucide="instagram" class="size-5"></i></a>
                        <a href="#" class="text-white opacity-75 hover-opacity-100"><i data-lucide="linkedin" class="size-5"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h3 class="fw-bold mb-4">Send us a Message</h3>
                <form action="{{ route('inquiries.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control py-3" required placeholder="John Doe" value="{{ old('name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control py-3" required placeholder="john@example.com" value="{{ old('email') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Subject</label>
                            <select class="form-select py-3">
                                <option selected>General Inquiry</option>
                                <option>Property Viewing</option>
                                <option>Selling a Property</option>
                                <option>Renting Information</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Your Message</label>
                            <textarea name="question" class="form-control" rows="6" required placeholder="Tell us how we can help you...">{{ old('question') }}</textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-sm">
                                <i data-lucide="send" class="size-4 me-2"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Map Placeholder -->
    <div class="mt-5 pt-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="height: 400px; background-color: #e5e7eb;">
            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                <div class="text-center">
                    <i data-lucide="map" class="size-12 mb-3 opacity-25"></i>
                    <p class="fw-bold">Interactive Map Coming Soon</p>
                    <p class="small">123 Real Estate St, City, Country</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
