<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ $appearance ?? 'auto' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ config('app.name', 'UISR') }} - Research Dataset Repository</title>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom styles -->
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .feature-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .academic-badge {
            background: rgba(255,255,255,0.1);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .cta-section {
            background: #f8f9fa;
        }
        [data-bs-theme="dark"] .cta-section {
            background: #212529;
        }
        [data-bs-theme="dark"] .feature-card {
            background: #343a40;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
                <i class="bi bi-database-fill text-primary me-2"></i>
                UISR
            </a>
            
            <div class="navbar-nav ms-auto">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="academic-badge mb-4">
                        🎓 Universitas Muhammadiyah Semarang
                    </div>
                    <h1 class="display-4 fw-bold mb-4">
                        📊 Unimus Intelligent System Repository
                    </h1>
                    <p class="lead mb-4">
                        Advanced research dataset repository supporting academic excellence through collaborative data sharing and professional curation workflows.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-person-plus me-2"></i>
                                Join UISR Community
                            </a>
                            <a href="{{ route('datasets.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-search me-2"></i>
                                Browse Datasets
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Go to Dashboard
                            </a>
                            <a href="{{ route('datasets.create') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-upload me-2"></i>
                                Submit Dataset
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-3">🚀 Comprehensive Research Platform</h2>
                    <p class="lead text-muted">
                        Supporting academic institutions with professional-grade data management, quality assurance, and collaborative research tools.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="display-4 mb-3">📤</div>
                            <h5 class="card-title">Dataset Submission</h5>
                            <p class="card-text">
                                Upload and manage research datasets with comprehensive metadata, version control, and collaboration features.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="display-4 mb-3">✅</div>
                            <h5 class="card-title">Quality Curation</h5>
                            <p class="card-text">
                                Professional review workflow with expert curation ensuring dataset quality, completeness, and academic standards.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="display-4 mb-3">📖</div>
                            <h5 class="card-title">Citation Generator</h5>
                            <p class="card-text">
                                Automated APA and IEEE format citations for academic publications with DOI integration and metadata export.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="display-4 mb-3">📈</div>
                            <h5 class="card-title">Accreditation Reports</h5>
                            <p class="card-text">
                                Comprehensive analytics and reporting for institutional assessment and academic accreditation requirements.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Collaboration Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-3">🌍 Global Research Collaboration</h2>
                    <p class="lead mb-4">
                        Track and manage local, national, and international research collaborations with detailed reporting capabilities for academic accreditation and institutional excellence.
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="display-6 fw-bold">500+</div>
                                <small>Active Researchers</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="display-6 fw-bold">1,200+</div>
                                <small>Published Datasets</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="display-6 fw-bold">50+</div>
                                <small>Partner Institutions</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <div class="bg-white bg-opacity-10 rounded-3 p-5">
                            <i class="bi bi-globe2 display-1 mb-3"></i>
                            <h4>Multi-Institutional Network</h4>
                            <p>Connecting researchers across universities and research institutions worldwide</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Research Impact Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="display-6 fw-bold">🎯 Research Impact & Recognition</h2>
                    <p class="lead text-muted">Supporting academic excellence through data-driven research collaboration</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-success h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-award me-2"></i>Quality Assurance</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success me-2"></i>Peer review process</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Metadata validation</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Version control</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Format standardization</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-info h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-people me-2"></i>Collaboration Tools</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-info me-2"></i>Team management</li>
                                <li><i class="bi bi-check-circle text-info me-2"></i>Access permissions</li>
                                <li><i class="bi bi-check-circle text-info me-2"></i>Discussion forums</li>
                                <li><i class="bi bi-check-circle text-info me-2"></i>Progress tracking</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-warning h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Analytics & Insights</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-warning me-2"></i>Usage statistics</li>
                                <li><i class="bi bi-check-circle text-warning me-2"></i>Citation tracking</li>
                                <li><i class="bi bi-check-circle text-warning me-2"></i>Impact metrics</li>
                                <li><i class="bi bi-check-circle text-warning me-2"></i>Research trends</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    @guest
                        <h2 class="display-6 fw-bold mb-3">Ready to Advance Your Research?</h2>
                        <p class="lead mb-4">
                            Join thousands of researchers worldwide using UISR to share, discover, and collaborate on groundbreaking research datasets.
                        </p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>
                                Create Account
                            </a>
                            <a href="{{ route('datasets.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-search me-2"></i>
                                Explore Datasets
                            </a>
                        </div>
                    @else
                        <h2 class="display-6 fw-bold mb-3">Welcome Back, {{ auth()->user()->name }}! 👋</h2>
                        <p class="lead mb-4">
                            Continue your research journey with access to your datasets, collaborations, and the latest academic resources.
                        </p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Open Dashboard
                            </a>
                            <a href="{{ route('datasets.create') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-upload me-2"></i>
                                Submit New Dataset
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-database-fill me-2"></i>UISR</h5>
                    <p class="text-muted mb-0">
                        Universitas Muhammadiyah Semarang<br>
                        Research Data Repository Platform
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        🎯 Supporting academic excellence through data sharing
                    </p>
                    <small class="text-muted">
                        © {{ date('Y') }} Unimus Intelligent System Repository
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Theme Switcher -->
    <script>
        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        }

        // Initialize theme on page load
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'auto';
            setTheme(savedTheme);
        })();
    </script>
</body>
</html>