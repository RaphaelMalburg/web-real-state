@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Add New Property</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Property Title</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" required value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="Sale" @if(old('type') === 'Sale') selected @endif>For Sale</option>
                                    <option value="Rent" @if(old('type') === 'Rent') selected @endif>For Rent</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <div id="ai-description-tools" class="d-flex flex-wrap gap-2 mb-2" data-generate-url="{{ route('admin.ai.description') }}" data-improve-url="{{ route('admin.ai.description_improve') }}">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-ai-action="generate">
                                        Generate Description
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-ai-action="improve">
                                        Polish Description
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                    </button>
                                </div>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                                <div id="ai-description-status" class="small mt-2 d-none"></div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" required step="0.01" value="{{ old('price') }}">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bedrooms</label>
                                <input type="number" name="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" value="{{ old('bedrooms', 0) }}">
                                @error('bedrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bathrooms</label>
                                <input type="number" name="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" value="{{ old('bathrooms', 0) }}">
                                @error('bathrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sqft</label>
                                <input type="number" name="sqft" class="form-control @error('sqft') is-invalid @enderror" value="{{ old('sqft', 0) }}">
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
                                <div class="mt-2">
                                    <div id="ai-image-tools" class="d-flex flex-wrap gap-2" data-generate-url="{{ route('admin.ai.image') }}">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-ai-image-action="generate">
                                            Generate Main Image
                                            <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                        </button>
                                        <input type="text" id="ai-image-style" class="form-control form-control-sm" placeholder="Optional style notes">
                                    </div>
                                    <input type="hidden" name="generated_image_url" id="generated_image_url">
                                    <div id="ai-image-preview" class="mt-2 d-none">
                                        <img id="generated_image_preview" src="" alt="Generated property image" class="img-thumbnail" style="max-height: 160px;">
                                    </div>
                                    <div id="ai-image-status" class="small mt-2 d-none"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Gallery Images (Multiple selection allowed)</label>
                                <input type="file" name="gallery_images[]" class="form-control @error('gallery_images') is-invalid @enderror" multiple accept="image/*">
                                @error('gallery_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($errors->has('gallery_images.*'))
                                    <div class="text-danger small mt-1">One or more files are invalid.</div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Property</button>
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

        const statusEl = document.getElementById('ai-description-status');

        const setStatus = (message, variant) => {
            if (!statusEl) {
                return;
            }
            statusEl.className = `small mt-2 ${variant}`;
            statusEl.textContent = message;
            statusEl.classList.remove('d-none');
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
                const spinner = button.querySelector('[data-spinner]');
                spinner?.classList.remove('d-none');
                setStatus('Working on it...', 'text-muted');

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
                        setStatus('Description updated.', 'text-success');
                    }
                } catch (error) {
                    setStatus(error?.message || 'Unable to generate description.', 'text-danger');
                } finally {
                    button.disabled = false;
                    button.classList.remove('disabled');
                    spinner?.classList.add('d-none');
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const imageTools = document.getElementById('ai-image-tools');
        if (!imageTools) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const imageButton = imageTools.querySelector('[data-ai-image-action="generate"]');
        const styleInput = document.getElementById('ai-image-style');
        const hiddenInput = document.getElementById('generated_image_url');
        const previewWrapper = document.getElementById('ai-image-preview');
        const previewImage = document.getElementById('generated_image_preview');
        const fileInput = document.querySelector('input[name="image_url"]');
        const statusEl = document.getElementById('ai-image-status');

        const setStatus = (message, variant) => {
            if (!statusEl) {
                return;
            }
            statusEl.className = `small mt-2 ${variant}`;
            statusEl.textContent = message;
            statusEl.classList.remove('d-none');
        };

        const getImagePayload = () => ({
            title: document.querySelector('[name="title"]')?.value || '',
            type: document.querySelector('[name="type"]')?.value || '',
            price: document.querySelector('[name="price"]')?.value || '',
            address: document.querySelector('[name="address"]')?.value || '',
            bedrooms: document.querySelector('[name="bedrooms"]')?.value || '',
            bathrooms: document.querySelector('[name="bathrooms"]')?.value || '',
            sqft: document.querySelector('[name="sqft"]')?.value || '',
            style: styleInput?.value || ''
        });

        imageButton?.addEventListener('click', async () => {
            const url = imageTools.dataset.generateUrl;
            if (!url) {
                return;
            }

            imageButton.disabled = true;
            imageButton.classList.add('disabled');
            const spinner = imageButton.querySelector('[data-spinner]');
            spinner?.classList.remove('d-none');
            setStatus('Generating image...', 'text-muted');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify(getImagePayload())
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data?.message || 'Unable to generate image.');
                }

                if (data?.url && data?.path) {
                    previewImage.src = data.url;
                    previewWrapper.classList.remove('d-none');
                    hiddenInput.value = data.path;
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    setStatus('Image generated.', 'text-success');
                }
            } catch (error) {
                setStatus(error?.message || 'Unable to generate image.', 'text-danger');
            } finally {
                imageButton.disabled = false;
                imageButton.classList.remove('disabled');
                spinner?.classList.add('d-none');
            }
        });
    });
</script>
@endpush
