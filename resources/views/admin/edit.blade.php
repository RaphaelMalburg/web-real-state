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
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $property->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="Sale" @if(old('type', $property->type) === 'Sale') selected @endif>For Sale</option>
                                    <option value="Rent" @if(old('type', $property->type) === 'Rent') selected @endif>For Rent</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <div id="ai-description-tools" class="d-flex flex-wrap gap-2 mb-2" data-generate-url="{{ route('admin.ai.description') }}" data-improve-url="{{ route('admin.ai.description_improve') }}">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-ai-action="generate">Generate Description</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-ai-action="improve">Polish Description</button>
                                </div>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $property->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $property->price) }}" required step="0.01">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $property->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bedrooms</label>
                                <input type="number" name="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" value="{{ old('bedrooms', $property->bedrooms) }}">
                                @error('bedrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bathrooms</label>
                                <input type="number" name="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" value="{{ old('bathrooms', $property->bathrooms) }}">
                                @error('bathrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sqft</label>
                                <input type="number" name="sqft" class="form-control @error('sqft') is-invalid @enderror" value="{{ old('sqft', $property->sqft) }}">
                                @error('sqft')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Main Image</label>
                                <input type="file" name="image_url" class="form-control @error('image_url') is-invalid @enderror" accept="image/*">
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($property->image_url)
                                    <div class="mt-2">
                                        <small>Current Image:</small>
                                        <img src="{{ $property->image_url }}" alt="Property Image" class="img-thumbnail d-block mt-1" style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">Gallery Images (Add new images)</label>
                                <input type="file" name="gallery_images[]" class="form-control @error('gallery_images') is-invalid @enderror" multiple accept="image/*">
                                @error('gallery_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($property->gallery_images)
                                    <div class="mt-2">
                                        <small>Current Gallery (URLs/Base64):</small>
                                        <div class="d-flex gap-2 flex-wrap mt-1">
                                            @php
                                                // Handle both JSON and legacy comma-separated
                                                $gallery = json_decode($property->gallery_images, true);
                                                if (!is_array($gallery)) {
                                                    $gallery = array_filter(explode(',', $property->gallery_images));
                                                }
                                            @endphp
                                            @foreach($gallery as $img)
                                                <img src="{{ Str::startsWith($img, 'data:') ? $img : asset(trim($img)) }}" class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover;">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tools = document.getElementById('ai-description-tools');
        if (!tools) {
            return;
        }

        const descriptionField = document.querySelector('[name="description"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const getPayload = (action) => {
            if (action === 'improve') {
                return { description: descriptionField?.value || '' };
            }

            return {
                title: document.querySelector('[name="title"]')?.value || '',
                type: document.querySelector('[name="type"]')?.value || '',
                price: document.querySelector('[name="price"]')?.value || '',
                address: document.querySelector('[name="address"]')?.value || '',
                bedrooms: document.querySelector('[name="bedrooms"]')?.value || '',
                bathrooms: document.querySelector('[name="bathrooms"]')?.value || '',
                sqft: document.querySelector('[name="sqft"]')?.value || ''
            };
        };

        tools.querySelectorAll('[data-ai-action]').forEach((button) => {
            button.addEventListener('click', async () => {
                const action = button.getAttribute('data-ai-action');
                const url = action === 'improve' ? tools.dataset.improveUrl : tools.dataset.generateUrl;
                if (!url || !descriptionField) {
                    return;
                }

                button.disabled = true;
                button.classList.add('disabled');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify(getPayload(action))
                    });

                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data?.message || 'Unable to generate description.');
                    }

                    if (data?.text) {
                        descriptionField.value = data.text;
                        descriptionField.dispatchEvent(new Event('input'));
                    }
                } catch (error) {
                    window.alert(error?.message || 'Unable to generate description.');
                } finally {
                    button.disabled = false;
                    button.classList.remove('disabled');
                }
            });
        });
    });
</script>
@endpush
