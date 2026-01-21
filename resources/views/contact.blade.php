@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4">Contact Us</h1>
                    <p class="text-center text-muted mb-5">Have a question? We're here to help. Send us a message and we'll respond as soon as possible.</p>

                    <form action="{{ route('inquiries.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter your name">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Your Question</label>
                            <textarea name="question" class="form-control" rows="5" required placeholder="How can we help you?"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
