<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstatePro Agency - Laravel Version</title>
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-lg-3" href="{{ route('admin.index') }}">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
