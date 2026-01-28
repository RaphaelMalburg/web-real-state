<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EstatePro Agency - Your trusted partner for luxury real estate, urban apartments, and modern homes. Buy, sell, or rent with confidence.">
    <meta name="keywords" content="real estate, luxury homes, apartments for rent, houses for sale, property management">
    <title>@yield('title', 'EstatePro Agency - Luxury Real Estate & Modern Living')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i data-lucide="home" class="d-inline-block align-text-top me-2"></i>
                EstatePro Agency
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}" @if(request()->routeIs('gallery')) aria-current="page" @endif>Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.index') }}" title="Admin Login" @if(request()->routeIs('admin.*')) aria-current="page" @endif>
                            <i data-lucide="user" class="align-text-top"></i>
                        </a>
                    </li>
                    @if(session('admin_logged_in'))
                        <li class="nav-item ms-lg-2">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-danger border-0 p-0 align-baseline">
                                    <i data-lucide="log-out" class="align-text-top"></i>
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true" data-bs-delay="4500">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
                <div class="d-flex">
                    <div class="toast-body">
                        <div class="fw-semibold mb-1">Please fix the highlighted fields.</div>
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>EstatePro Agency</h5>
                    <p class="text-muted">Providing high-quality real estate services since 2010. Your dream home is just a click away.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-decoration-none text-muted">About Us</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-decoration-none text-muted">Gallery</a></li>
                        <li><a href="{{ route('contact') }}" class="text-decoration-none text-muted">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Info</h5>
                    <p class="text-muted mb-1"><i data-lucide="map-pin" class="me-2 size-4"></i> 123 Real Estate St, City, Country</p>
                    <p class="text-muted mb-1"><i data-lucide="phone" class="me-2 size-4"></i> +1 234 567 890</p>
                    <p class="text-muted"><i data-lucide="mail" class="me-2 size-4"></i> info@estatepro.com</p>
                </div>
            </div>
            <hr>
            <div class="text-center text-muted">
                <small>&copy; {{ date('Y') }} EstatePro Agency. Laravel Herd PHP 8.4 Version.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            document.querySelectorAll('.toast').forEach((toastEl) => {
                const delay = Number.parseInt(toastEl.getAttribute('data-bs-delay') || '4500', 10);
                const toast = new bootstrap.Toast(toastEl, { delay });
                toast.show();
            });

            // Global Form Loading State
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('no-loader')) {
                        const originalHtml = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...`;
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
