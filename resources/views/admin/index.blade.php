@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <a href="{{ route('admin.create') }}" class="btn btn-success">
            <i data-lucide="plus-circle" class="me-2 size-5"></i> Add New Property
        </a>
    </div>

    <!-- Properties Management -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Properties</h5>
            <span class="badge bg-primary">{{ $properties->count() }} Total</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($properties as $prop)
                    <tr>
                        <td>#{{ $prop->id }}</td>
                        <td>{{ $prop->title }}</td>
                        <td><span class="badge {{ $prop->type === 'Sale' ? 'bg-success' : 'bg-info' }}">{{ $prop->type }}</span></td>
                        <td>${{ number_format($prop->price) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.edit', $prop) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i data-lucide="edit-2" class="size-4"></i> Edit
                            </a>
                            <a href="{{ route('admin.delete', $prop) }}" class="btn btn-sm btn-outline-danger">
                                <i data-lucide="trash-2" class="size-4"></i> Remove
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <!-- Bookings Management -->
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Bookings</h5>
                    <span class="badge bg-warning text-dark">{{ $bookings->count() }} Pending</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Property</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $booking->user_name }}</div>
                                    <small class="text-muted">{{ $booking->user_email }}</small>
                                </td>
                                <td>{{ $booking->property->title ?? 'N/A' }}</td>
                                <td>{{ $booking->booking_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inquiries Management -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Inquiries</h5>
                    <span class="badge bg-info">{{ $inquiries->count() }} Total</span>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($inquiries as $inquiry)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $inquiry->name }}</h6>
                            <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 small text-muted">{{ $inquiry->email }}</p>
                        <p class="mb-0 small">{{ Str::limit($inquiry->question, 100) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
