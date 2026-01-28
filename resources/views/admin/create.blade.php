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
                                <label class="form-label fw-bold">Main Image</label>
                                <div class="card bg-light border-dashed p-4 mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <input type="file" name="image_url" class="form-control @error('image_url') is-invalid @enderror" accept="image/*">
                                            <div class="form-text mt-2">Upload a high-resolution image or use AI to generate one.</div>
                                        </div>
                                        <div class="col-md-6 border-start ps-md-4 mt-3 mt-md-0">
                                            <div id="ai-image-tools" data-generate-url="{{ route('admin.ai.image') }}">
                                                <div class="d-flex gap-2 mb-2">
                                                    <input type="text" id="ai-image-style" class="form-control form-control-sm" placeholder="e.g. Modern, minimalist, garden view">
                                                    <button type="button" class="btn btn-primary btn-sm px-3" data-ai-image-action="generate">
                                                        <i data-lucide="sparkles" class="size-4 me-1"></i> Generate
                                                        <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                                    </button>
                                                </div>
                                                <div id="ai-image-status" class="small d-none"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="generated_image_url" id="generated_image_url">
                                    <div id="ai-image-preview" class="row g-3 mt-4 d-none text-center border-top pt-4">
                                        <!-- Unified preview for all AI generated images -->
                                    </div>
                                </div>
                                @error('image_url')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Gallery Images</label>
                                <div class="card bg-light border-dashed p-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <input type="file" name="gallery_images[]" class="form-control @error('gallery_images') is-invalid @enderror" multiple accept="image/*">
                                            <div class="form-text mt-2">Select multiple files to showcase different rooms and features.</div>
                                        </div>
                                        <div class="col-md-6 border-start ps-md-4 mt-3 mt-md-0">
                                            <div id="ai-gallery-tools" data-generate-url="{{ route('admin.ai.gallery') }}">
                                                <div class="d-flex gap-2 mb-2">
                                                    <div class="input-group input-group-sm" style="max-width: 120px;">
                                                        <span class="input-group-text bg-white">Qty</span>
                                                        <input type="number" id="ai-gallery-qty" class="form-control" value="4" min="1" max="4">
                                                    </div>
                                                    <button type="button" class="btn btn-primary btn-sm px-3" data-ai-gallery-action="generate">
                                                        <i data-lucide="layout-grid" class="size-4 me-1"></i> AI Gallery
                                                        <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                                    </button>
                                                </div>
                                                <div id="ai-gallery-status" class="small d-none"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="generated-gallery-inputs"></div>
                                    <div id="ai-gallery-preview" class="row g-3 mt-4 d-none text-center border-top pt-4">
                                        <!-- JS will inject interactive cards here -->
                                    </div>
                                </div>
                                @error('gallery_images')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
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
        const galleryTools = document.getElementById('ai-gallery-tools');

        if (!imageTools && !galleryTools) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const mainHiddenInput = document.getElementById('generated_image_url');
        const galleryInputsContainer = document.getElementById('generated-gallery-inputs');
        const imagePreview = document.getElementById('ai-image-preview');
        const galleryPreview = document.getElementById('ai-gallery-preview');

        // Track state
        let currentMainPath = null;
        const currentGalleryPaths = new Set();

        const updateBadges = () => {
            document.querySelectorAll('[data-generated-path]').forEach(card => {
                const path = card.dataset.generatedPath;
                const mainBadge = card.querySelector('[data-badge="main"]');
                const galleryBadge = card.querySelector('[data-badge="gallery"]');
                const mainBtn = card.querySelector('[data-action="set-main"]');
                const galleryBtn = card.querySelector('[data-action="set-gallery"]');

                if (path === currentMainPath) {
                    mainBadge?.classList.remove('d-none');
                    card.classList.add('border-primary');
                    if (mainBtn) mainBtn.classList.replace('btn-outline-primary', 'btn-primary');
                } else {
                    mainBadge?.classList.add('d-none');
                    card.classList.remove('border-primary');
                    if (mainBtn) mainBtn.classList.replace('btn-primary', 'btn-outline-primary');
                }

                if (currentGalleryPaths.has(path)) {
                    galleryBadge?.classList.remove('d-none');
                    card.classList.add('border-success');
                    if (galleryBtn) galleryBtn.classList.replace('btn-outline-success', 'btn-success');
                } else {
                    galleryBadge?.classList.add('d-none');
                    card.classList.remove('border-success');
                    if (galleryBtn) galleryBtn.classList.replace('btn-success', 'btn-outline-success');
                }
            });

            // Update hidden inputs
            if (mainHiddenInput) mainHiddenInput.value = currentMainPath || '';

            if (galleryInputsContainer) {
                galleryInputsContainer.innerHTML = '';
                currentGalleryPaths.forEach(path => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'generated_gallery_images[]';
                    input.value = path;
                    galleryInputsContainer.appendChild(input);
                });
            }
        };

        const createCard = (imgData) => {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            col.dataset.generatedPath = imgData.path;

            col.innerHTML = `
                <div class="card h-100 border shadow-sm position-relative overflow-hidden bg-white">
                    <img src="${imgData.url}" class="card-img-top" style="height: 100px; object-fit: cover;">
                    <div class="position-absolute top-0 start-0 p-1 d-flex flex-column gap-1">
                        <span class="badge bg-primary d-none" data-badge="main">Main</span>
                        <span class="badge bg-success d-none" data-badge="gallery">Gallery</span>
                    </div>
                    <div class="card-body p-2 d-flex flex-column gap-1">
                        <button type="button" class="btn btn-outline-primary btn-xs py-1" data-action="set-main" style="font-size: 0.7rem;">
                            Set Main
                        </button>
                        <button type="button" class="btn btn-outline-success btn-xs py-1" data-action="set-gallery" style="font-size: 0.7rem;">
                            Add Gallery
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-xs py-1" data-action="discard" style="font-size: 0.7rem;">
                            Discard
                        </button>
                    </div>
                </div>
            `;

            col.querySelector('[data-action="set-main"]').onclick = () => {
                currentMainPath = (currentMainPath === imgData.path) ? null : imgData.path;
                updateBadges();
            };

            col.querySelector('[data-action="set-gallery"]').onclick = () => {
                if (currentGalleryPaths.has(imgData.path)) {
                    currentGalleryPaths.delete(imgData.path);
                } else {
                    currentGalleryPaths.add(imgData.path);
                }
                updateBadges();
            };

            col.querySelector('[data-action="discard"]').onclick = () => {
                if (currentMainPath === imgData.path) currentMainPath = null;
                currentGalleryPaths.delete(imgData.path);
                col.remove();
                updateBadges();
            };

            return col;
        };

        const getCommonPayload = () => ({
            title: document.querySelector('[name="title"]')?.value || '',
            type: document.querySelector('[name="type"]')?.value || '',
            price: document.querySelector('[name="price"]')?.value || '',
            address: document.querySelector('[name="address"]')?.value || '',
            bedrooms: document.querySelector('[name="bedrooms"]')?.value || '',
            bathrooms: document.querySelector('[name="bathrooms"]')?.value || '',
            sqft: document.querySelector('[name="sqft"]')?.value || '',
            style: document.getElementById('ai-image-style')?.value || ''
        });

        // Single Image Generation
        imageTools?.querySelector('[data-ai-image-action="generate"]')?.addEventListener('click', async (e) => {
            const btn = e.currentTarget;
            const url = imageTools.dataset.generateUrl;
            const statusEl = document.getElementById('ai-image-status');
            const spinner = btn.querySelector('[data-spinner]');

            btn.disabled = true;
            spinner?.classList.remove('d-none');
            if (statusEl) {
                statusEl.textContent = 'Generating main image...';
                statusEl.className = 'small mt-2 text-muted';
                statusEl.classList.remove('d-none');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(getCommonPayload())
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Generation failed');

                if (data.url && data.path) {
                    const card = createCard(data);
                    imagePreview.appendChild(card);
                    imagePreview.classList.remove('d-none');
                    // Auto-set as main if none exists
                    if (!currentMainPath) currentMainPath = data.path;
                    updateBadges();
                    if (statusEl) {
                        statusEl.textContent = 'Image generated! Choose how to use it below.';
                        statusEl.className = 'small mt-2 text-success';
                    }
                }
            } catch (err) {
                if (statusEl) {
                    statusEl.textContent = err.message;
                    statusEl.className = 'small mt-2 text-danger';
                }
            } finally {
                btn.disabled = false;
                spinner?.classList.add('d-none');
            }
        });

        // Gallery Generation
        galleryTools?.querySelector('[data-ai-gallery-action="generate"]')?.addEventListener('click', async (e) => {
            const btn = e.currentTarget;
            const url = galleryTools.dataset.generateUrl;
            const statusEl = document.getElementById('ai-gallery-status');
            const spinner = btn.querySelector('[data-spinner]');
            const qty = document.getElementById('ai-gallery-qty')?.value || 4;

            btn.disabled = true;
            spinner?.classList.remove('d-none');
            if (statusEl) {
                statusEl.textContent = `Generating ${qty} gallery images...`;
                statusEl.className = 'small mt-2 text-muted';
                statusEl.classList.remove('d-none');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ ...getCommonPayload(), quantity: qty })
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Generation failed');

                if (data.images && Array.isArray(data.images)) {
                    data.images.forEach(img => {
                        const card = createCard(img);
                        galleryPreview.appendChild(card);
                        // Auto-add to gallery by default
                        currentGalleryPaths.add(img.path);
                    });
                    galleryPreview.classList.remove('d-none');
                    updateBadges();
                    if (statusEl) {
                        statusEl.textContent = 'Gallery images generated! You can reassign them if needed.';
                        statusEl.className = 'small mt-2 text-success';
                    }
                }
            } catch (err) {
                if (statusEl) {
                    statusEl.textContent = err.message;
                    statusEl.className = 'small mt-2 text-danger';
                }
            } finally {
                btn.disabled = false;
                spinner?.classList.add('d-none');
            }
        });
    });
</script>
@endpush
