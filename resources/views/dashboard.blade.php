@extends('layouts.app')

@section('title', 'Dashboard - UISR')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">📊 UISR Dashboard</h1>
                    <p class="text-muted mb-0">Welcome to your research dataset management dashboard</p>
                </div>
                <div>
                    <a href="{{ route('datasets.create') }}" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>
                        Submit Dataset
                    </a>
                </div>
            </div>

            <!-- Statistics Cards (for curators/admins) -->
            @if(isset($statistics))
            <div class="row g-3 mb-4">
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-primary mb-2">📊</div>
                            <div class="h3 fw-bold mb-0">{{ $statistics->totalDatasets }}</div>
                            <small class="text-muted">Total Datasets</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-success mb-2">👥</div>
                            <div class="h3 fw-bold mb-0">{{ $statistics->totalUsers }}</div>
                            <small class="text-muted">Total Users</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-info mb-2">👨‍🏫</div>
                            <div class="h3 fw-bold mb-0">{{ $statistics->totalLecturers }}</div>
                            <small class="text-muted">Lecturers</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-warning mb-2">👨‍🎓</div>
                            <div class="h3 fw-bold mb-0">{{ $statistics->totalStudents }}</div>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-primary mb-2">📈</div>
                            <div class="h3 fw-bold mb-0">{{ $statistics->datasetsThisMonth }}</div>
                            <small class="text-muted">This Month</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-danger mb-2">⏳</div>
                            <div class="h3 fw-bold mb-0">{{ $statistics->pendingReviews }}</div>
                            <small class="text-muted">Pending Reviews</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Content Row -->
            <div class="row g-4">
                <!-- My Datasets -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-folder-fill text-primary me-2"></i>
                                    My Datasets
                                </h5>
                                <a href="{{ route('datasets.create') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    New Dataset
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($userDatasets) > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($userDatasets as $dataset)
                                    <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <a href="{{ route('datasets.show', $dataset->id) }}" 
                                                   class="text-decoration-none fw-semibold text-primary">
                                                    {{ $dataset->title }}
                                                </a>
                                                <div class="mt-1">
                                                    @php
                                                        $statusClass = match($dataset->status) {
                                                            'published' => 'bg-success',
                                                            'under_review' => 'bg-warning',
                                                            'draft' => 'bg-secondary',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $statusClass }} me-2">{{ ucfirst(str_replace('_', ' ', $dataset->status)) }}</span>
                                                    <small class="text-muted">
                                                        {{ $dataset->files_count }} files • {{ $dataset->reviews_count }} reviews
                                                    </small>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $dataset->created_at->format('M j, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="display-4 text-muted mb-3">📊</div>
                                    <h5>No datasets yet</h5>
                                    <p class="text-muted mb-3">
                                        Start contributing to the research community by submitting your first dataset.
                                    </p>
                                    <a href="{{ route('datasets.create') }}" class="btn btn-primary">
                                        <i class="bi bi-upload me-1"></i>
                                        Submit Your First Dataset
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-clock-history text-info me-2"></i>
                                    Recent Activity
                                </h5>
                                <a href="{{ route('datasets.index') }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-arrow-right me-1"></i>
                                    Browse All
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($recentDatasets) > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentDatasets as $dataset)
                                    <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="bg-light rounded-circle p-2">
                                                    <i class="bi bi-database text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <a href="{{ route('datasets.show', $dataset->id) }}" 
                                                   class="text-decoration-none fw-semibold">
                                                    {{ Str::limit($dataset->title, 60) }}
                                                </a>
                                                <div class="small text-muted mt-1">
                                                    Recently published • {{ $dataset->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="display-4 text-muted mb-3">🕒</div>
                                    <p class="text-muted">No recent activity</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightning-fill text-warning me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('datasets.create') }}" class="text-decoration-none">
                                        <div class="card border border-primary bg-primary bg-opacity-10 h-100 hover-lift">
                                            <div class="card-body text-center py-4">
                                                <div class="display-6 text-primary mb-2">📤</div>
                                                <h6 class="fw-bold text-primary">Submit Dataset</h6>
                                                <small class="text-muted">Upload new research data</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('datasets.index') }}" class="text-decoration-none">
                                        <div class="card border border-info bg-info bg-opacity-10 h-100 hover-lift">
                                            <div class="card-body text-center py-4">
                                                <div class="display-6 text-info mb-2">🔍</div>
                                                <h6 class="fw-bold text-info">Browse Datasets</h6>
                                                <small class="text-muted">Explore published datasets</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('profile.show') }}" class="text-decoration-none">
                                        <div class="card border border-success bg-success bg-opacity-10 h-100 hover-lift">
                                            <div class="card-body text-center py-4">
                                                <div class="display-6 text-success mb-2">👤</div>
                                                <h6 class="fw-bold text-success">My Profile</h6>
                                                <small class="text-muted">Manage your profile</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                @if($canViewReports)
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('reports.index') }}" class="text-decoration-none">
                                        <div class="card border border-warning bg-warning bg-opacity-10 h-100 hover-lift">
                                            <div class="card-body text-center py-4">
                                                <div class="display-6 text-warning mb-2">📈</div>
                                                <h6 class="fw-bold text-warning">View Reports</h6>
                                                <small class="text-muted">Accreditation reports</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection