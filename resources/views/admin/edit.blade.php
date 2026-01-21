@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Edit Property: {{ $property->title }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update', $property) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Property Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $property->title }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="Sale" {{ $property->type === 'Sale' ? 'selected' : '' }}>For Sale</option>
                                    <option value="Rent" {{ $property->type === 'Rent' ? 'selected' : '' }}>For Rent</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" required>{{ $property->description }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price" class="form-control" value="{{ $property->price }}" required step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $property->address }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bedrooms</label>
                                <input type="number" name="bedrooms" class="form-control" value="{{ $property->bedrooms }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bathrooms</label>
                                <input type="number" name="bathrooms" class="form-control" value="{{ $property->bathrooms }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sqft</label>
                                <input type="number" name="sqft" class="form-control" value="{{ $property->sqft }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Main Image</label>
                                <input type="file" name="image_url" class="form-control" accept="image/*">
                                @if($property->image_url)
                                    <div class="mt-2">
                                        <small>Current Image:</small>
                                        <img src="{{ $property->image_url }}" alt="Property Image" class="img-thumbnail d-block mt-1" style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">Gallery Images (comma separated)</label>
                                <input type="text" name="gallery_images" class="form-control" value="{{ $property->gallery_images }}">
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Property</button>
                            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
