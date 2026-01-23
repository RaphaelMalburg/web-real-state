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
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-ai-action="generate">
                                        Generate Description
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-ai-action="improve">
                                        Polish Description
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                    </button>
                                </div>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $property->description) }}</textarea>
                                <div id="ai-description-status" class="small mt-2 d-none"></div>
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
                                @if($property->image_url)
                                    <div class="mt-2">
                                        <small>Current Image:</small>
                                        <div id="current-image-wrapper" class="d-flex align-items-center gap-3 mt-1 flex-wrap">
                                            <img src="{{ Str::startsWith($property->image_url, 'data:') ? $property->image_url : asset($property->image_url) }}" alt="Property Image" class="img-thumbnail" style="max-height: 150px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remove_image_url" id="remove_image_url" value="1">
                                                <label class="form-check-label" for="remove_image_url">Remove main image</label>
                                            </div>
                                            <span id="current-image-flag" class="badge bg-warning text-dark d-none">Marked for removal</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">Gallery Images (Add new images)</label>
                                <input type="file" name="gallery_images[]" class="form-control @error('gallery_images') is-invalid @enderror" multiple accept="image/*">
                                <div id="ai-gallery-tools" class="d-flex flex-wrap gap-2 mt-2 align-items-center" data-generate-url="{{ route('admin.ai.gallery') }}">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-ai-gallery-action="generate">
                                        Generate Gallery Images
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" data-spinner></span>
                                    </button>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" id="ai-gallery-qty" class="form-control" value="2" min="1" max="4">
                                    </div>
                                </div>
                                <div id="generated-gallery-inputs"></div>
                                <div id="ai-gallery-preview" class="d-flex gap-2 flex-wrap mt-2"></div>
                                <div id="ai-gallery-status" class="small mt-2 d-none"></div>
                                @error('gallery_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($property->gallery_images)
                                    <div class="mt-2">
                                        <small>Current Gallery:</small>
                                        <div class="d-flex gap-2 flex-wrap mt-1">
                                            @php
                                                // Handle both JSON and legacy comma-separated
                                                $gallery = json_decode($property->gallery_images, true);
                                                if (!is_array($gallery)) {
                                                    $gallery = array_filter(explode(',', $property->gallery_images));
                                                }
                                            @endphp
                                            @foreach($gallery as $img)
                                                <div class="d-flex flex-column align-items-center gap-1" data-gallery-item>
                                                    <img src="{{ Str::startsWith($img, 'data:') ? $img : asset(trim($img)) }}" class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="remove_gallery_images[]" value="{{ trim($img) }}">
                                                        <label class="form-check-label small">Remove</label>
                                                    </div>
                                                    <span class="badge bg-warning text-dark d-none" data-remove-flag>Marked for removal</span>
                                                </div>
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
        const removeCheckbox = document.getElementById('remove_image_url');
        const currentImageWrapper = document.getElementById('current-image-wrapper');
        const currentImageFlag = document.getElementById('current-image-flag');
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
                    if (removeCheckbox) {
                        removeCheckbox.checked = false;
                    }
                    if (currentImageWrapper) {
                        currentImageWrapper.classList.remove('opacity-50');
                    }
                    if (currentImageFlag) {
                        currentImageFlag.classList.add('d-none');
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

        removeCheckbox?.addEventListener('change', () => {
            const isChecked = removeCheckbox.checked;
            hiddenInput.value = '';
            if (previewWrapper) {
                previewWrapper.classList.add('d-none');
            }
            if (currentImageWrapper) {
                currentImageWrapper.classList.toggle('opacity-50', isChecked);
            }
            if (currentImageFlag) {
                currentImageFlag.classList.toggle('d-none', !isChecked);
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const galleryTools = document.getElementById('ai-gallery-tools');
        if (!galleryTools) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const generateButton = galleryTools.querySelector('[data-ai-gallery-action="generate"]');
        const qtyInput = document.getElementById('ai-gallery-qty');
        const inputsContainer = document.getElementById('generated-gallery-inputs');
        const previewContainer = document.getElementById('ai-gallery-preview');
        const styleInput = document.getElementById('ai-image-style');
        const statusEl = document.getElementById('ai-gallery-status');

        const setStatus = (message, variant) => {
            if (!statusEl) {
                return;
            }
            statusEl.className = `small mt-2 ${variant}`;
            statusEl.textContent = message;
            statusEl.classList.remove('d-none');
        };

        const getGalleryPayload = () => ({
            title: document.querySelector('[name="title"]')?.value || '',
            type: document.querySelector('[name="type"]')?.value || '',
            price: document.querySelector('[name="price"]')?.value || '',
            address: document.querySelector('[name="address"]')?.value || '',
            bedrooms: document.querySelector('[name="bedrooms"]')?.value || '',
            bathrooms: document.querySelector('[name="bathrooms"]')?.value || '',
            sqft: document.querySelector('[name="sqft"]')?.value || '',
            style: styleInput?.value || '',
            quantity: qtyInput?.value || 2
        });

        generateButton?.addEventListener('click', async () => {
            const url = galleryTools.dataset.generateUrl;
            if (!url) {
                return;
            }

            generateButton.disabled = true;
            generateButton.classList.add('disabled');
            generateButton.textContent = 'Generating...';
            const spinner = generateButton.querySelector('[data-spinner]');
            spinner?.classList.remove('d-none');
            setStatus('Generating gallery images...', 'text-muted');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify(getGalleryPayload())
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data?.message || 'Unable to generate gallery images.');
                }

                if (data?.images && Array.isArray(data.images)) {
                    data.images.forEach(img => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'generated_gallery_images[]';
                        input.value = img.path;
                        inputsContainer.appendChild(input);

                        const previewWrapper = document.createElement('div');
                        previewWrapper.className = 'd-flex flex-column align-items-center gap-1 position-relative';

                        const imgEl = document.createElement('img');
                        imgEl.src = img.url;
                        imgEl.className = 'img-thumbnail';
                        imgEl.style = 'height: 80px; width: 80px; object-fit: cover;';

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-danger btn-sm p-0 rounded-circle position-absolute top-0 end-0';
                        removeBtn.style = 'width: 20px; height: 20px; line-height: 1; transform: translate(30%, -30%);';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.onclick = () => {
                            previewWrapper.remove();
                            input.remove();
                        };

                        previewWrapper.appendChild(imgEl);
                        previewWrapper.appendChild(removeBtn);
                        previewContainer.appendChild(previewWrapper);
                    });
                    setStatus('Gallery images generated.', 'text-success');
                }
            } catch (error) {
                setStatus(error?.message || 'Unable to generate gallery images.', 'text-danger');
            } finally {
                generateButton.disabled = false;
                generateButton.classList.remove('disabled');
                generateButton.textContent = 'Generate Gallery Images';
                spinner?.classList.add('d-none');
            }
        });

        document.querySelectorAll('[data-gallery-item]').forEach((item) => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            const flag = item.querySelector('[data-remove-flag]');
            checkbox?.addEventListener('change', () => {
                const isChecked = checkbox.checked;
                item.classList.toggle('opacity-50', isChecked);
                flag?.classList.toggle('d-none', !isChecked);
            });
        });
    });
</script>
@endpush
