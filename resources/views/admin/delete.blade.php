@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-4 d-inline-block mb-4">
                        <i data-lucide="alert-triangle" class="size-12 text-danger"></i>
                    </div>
                    
                    <h2 class="fw-bold text-dark mb-3">Delete Property?</h2>
                    <p class="text-muted mb-5">You are about to remove <span class="fw-bold text-dark">"{{ $property->title }}"</span>. This action is permanent and cannot be undone.</p>

                    <div class="bg-light rounded-4 p-4 mb-5 text-start">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="small text-muted fw-bold text-uppercase">Address</div>
                                <div class="text-dark">{{ $property->address }}</div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted fw-bold text-uppercase">Type</div>
                                <div class="text-dark">{{ $property->type }}</div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted fw-bold text-uppercase">Price</div>
                                <div class="text-dark">${{ number_format($property->price) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <form action="{{ route('admin.destroy', $property) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold py-3 shadow-sm">
                                <i data-lucide="trash-2" class="size-5 me-2"></i> Confirm Deletion
                            </button>
                        </form>
                        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill fw-bold py-3">
                            No, Keep Property
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
