@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Admin Dashboard</h1>
        <a href="{{ route('admin.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i data-lucide="plus-circle" class="me-2 size-5"></i> New Property
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i data-lucide="home" class="size-6 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">Total Properties</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total_properties'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                        <i data-lucide="message-square" class="size-6 text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">Active Inquiries</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total_inquiries'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                        <i data-lucide="calendar" class="size-6 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">Bookings</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total_bookings'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                        <i data-lucide="dollar-sign" class="size-6 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">Market Value</div>
                        <div class="h4 fw-bold mb-0">${{ number_format($stats['market_value'] / 1000000, 1) }}M</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Management -->
    <div class="card shadow-sm border-0 rounded-4 mb-5 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Manage Properties</h5>
            <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">{{ $properties->count() }} Total</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
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
                    @forelse ($properties as $prop)
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
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No properties yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <!-- Bookings Management -->
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Bookings</h5>
                    <span class="badge bg-light text-warning border px-3 py-2 rounded-pill">{{ $bookings->count() }} Pending</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead>
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Property</th>
                                <th class="pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $booking->user_name }}</div>
                                    <small class="text-muted">{{ $booking->user_email }}</small>
                                </td>
                                <td><span class="text-truncate d-inline-block" style="max-width: 150px;">{{ $booking->property->title ?? 'N/A' }}</span></td>
                                <td class="pe-4">{{ $booking->booking_date }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5">No bookings yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inquiries Management -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Inquiries</h5>
                    <span class="badge bg-light text-info border px-3 py-2 rounded-pill">{{ $inquiries->count() }} Total</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($inquiries as $inquiry)
                    <div class="list-group-item border-0 border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold text-dark mb-0">{{ $inquiry->name }}</h6>
                            <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="text-muted small mb-2">{{ Str::limit($inquiry->question, 100) }}</p>
                        <div class="small fw-bold"><i data-lucide="mail" class="size-3 me-1"></i> {{ $inquiry->email }}</div>
                    </div>
                    @empty
                    <div class="p-5 text-center text-muted">No inquiries yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
