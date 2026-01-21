@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Confirm Removal</h5>
                </div>
                <div class="card-body">
                    <p class="text-danger fw-bold">Are you sure you want to remove this property? This action cannot be undone.</p>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Property Title</label>
                            <input type="text" class="form-control" value="{{ $property->title }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Type</label>
                            <input type="text" class="form-control" value="{{ $property->type }}" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" value="{{ $property->address }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price ($)</label>
                            <input type="text" class="form-control" value="{{ number_format($property->price) }}" disabled>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <form action="{{ route('admin.destroy', $property) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Confirm & Delete</button>
                        </form>
                        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
