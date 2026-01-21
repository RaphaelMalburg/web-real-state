@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-5">Property Gallery</h1>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Property Title</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $prop)
                <tr>
                    <td>
                        <img src="{{ Str::startsWith($prop->image_url, 'data:') ? $prop->image_url : asset($prop->image_url) }}"
                             class="gallery-img rounded"
                             alt="{{ $prop->title }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg') }}';">
                    </td>
                    <td>
                        <div class="fw-bold">{{ $prop->title }}</div>
                        <small class="text-muted">{{ Str::limit($prop->description, 50) }}</small>
                    </td>
                    <td>
                        <span class="badge {{ $prop->type === 'Sale' ? 'bg-success' : 'bg-info' }}">
                            {{ $prop->type }}
                        </span>
                    </td>
                    <td>
                        ${{ number_format($prop->price) }} {{ $prop->type === 'Rent' ? '/ mo' : '' }}
                    </td>
                    <td class="text-end">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
