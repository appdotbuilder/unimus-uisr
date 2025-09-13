@extends('layouts.app')

@section('title', 'Browse Datasets - UISR')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">
                        <i class="bi bi-folder-fill text-primary me-2"></i>
                        Research Datasets
                    </h1>
                    <p class="text-muted mb-0">Discover and access published research datasets from the academic community</p>
                </div>
                @auth
                <div>
                    <a href="{{ route('datasets.create') }}" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>
                        Submit Dataset
                    </a>
                </div>
                @endauth
            </div>

            <!-- Search and Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('datasets.index') }}" class="row g-3">
                        <div class="col-lg-4">
                            <label for="search" class="form-label">Search Datasets</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Enter keywords, title, or author..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title A-Z</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="per_page" class="form-label">Per Page</label>
                            <select class="form-select" id="per_page" name="per_page">
                                <option value="10" {{ request('per_page', '10') === '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') === '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') === '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div class="col-lg-2 d-flex align-items-end">
                            <div class="d-grid w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel me-1"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Summary -->
            @if(request()->hasAny(['search', 'status', 'sort']))
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Showing {{ $datasets->firstItem() ?? 0 }} to {{ $datasets->lastItem() ?? 0 }} 
                of {{ $datasets->total() }} results
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
            </div>
            @endif

            <!-- Datasets Grid -->
            @if($datasets->count() > 0)
                <div class="row g-4">
                    @foreach($datasets as $dataset)
                    <div class="col-lg-6 col-xl-4">
                        <div class="card border-0 shadow-sm h-100 dataset-card">
                            <div class="card-body p-4">
                                <!-- Status Badge -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    @php
                                        $statusClass = match($dataset->status) {
                                            'published' => 'bg-success',
                                            'under_review' => 'bg-warning text-dark',
                                            'draft' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $dataset->status)) }}
                                    </span>
                                    <small class="text-muted">{{ $dataset->created_at->format('M j, Y') }}</small>
                                </div>

                                <!-- Title and Author -->
                                <h5 class="card-title">
                                    <a href="{{ route('datasets.show', $dataset) }}" 
                                       class="text-decoration-none text-primary">
                                        {{ $dataset->title }}
                                    </a>
                                </h5>
                                
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-person me-1"></i>
                                    by {{ $dataset->user->name }}
                                    @if($dataset->user->profile && $dataset->user->profile->affiliation)
                                        • {{ $dataset->user->profile->affiliation }}
                                    @endif
                                </p>

                                <!-- Description -->
                                @if($dataset->description)
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($dataset->description, 120) }}
                                </p>
                                @endif

                                <!-- Keywords -->
                                @if($dataset->keywords)
                                <div class="mb-3">
                                    @foreach(explode(',', $dataset->keywords) as $keyword)
                                        @if(trim($keyword))
                                        <span class="badge bg-light text-dark me-1 mb-1">{{ trim($keyword) }}</span>
                                        @endif
                                    @endforeach
                                </div>
                                @endif

                                <!-- Meta Information -->
                                <div class="row g-2 text-muted small">
                                    <div class="col-6">
                                        <i class="bi bi-file-earmark me-1"></i>
                                        {{ $dataset->files_count }} {{ Str::plural('file', $dataset->files_count) }}
                                    </div>
                                    <div class="col-6">
                                        <i class="bi bi-eye me-1"></i>
                                        {{ $dataset->views_count ?? 0 }} {{ Str::plural('view', $dataset->views_count ?? 0) }}
                                    </div>
                                    @if($dataset->reviews_count > 0)
                                    <div class="col-6">
                                        <i class="bi bi-chat-square-dots me-1"></i>
                                        {{ $dataset->reviews_count }} {{ Str::plural('review', $dataset->reviews_count) }}
                                    </div>
                                    @endif
                                    @if($dataset->license)
                                    <div class="col-6">
                                        <i class="bi bi-shield-check me-1"></i>
                                        {{ $dataset->license }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Card Footer -->
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('datasets.show', $dataset) }}" 
                                       class="btn btn-primary btn-sm flex-fill">
                                        <i class="bi bi-eye me-1"></i>
                                        View Details
                                    </a>
                                    @if($dataset->status === 'published')
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            onclick="copyDatasetCitation('{{ $dataset->id }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $datasets->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-5">
                    <div class="display-4 text-muted mb-4">🔍</div>
                    <h3>No datasets found</h3>
                    @if(request()->hasAny(['search', 'status']))
                        <p class="text-muted mb-4">Try adjusting your search criteria or browse all datasets.</p>
                        <a href="{{ route('datasets.index') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Clear Filters
                        </a>
                    @else
                        <p class="text-muted mb-4">Be the first to contribute to our research community!</p>
                    @endif
                    @auth
                        <a href="{{ route('datasets.create') }}" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i>
                            Submit Dataset
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>
                            Join to Submit
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .dataset-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .dataset-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
</style>
@endpush

@push('scripts')
<script>
function copyDatasetCitation(datasetId) {
    // This would be implemented to copy citation format
    // For now, just show a toast notification
    alert('Citation format copied to clipboard!');
}
</script>
@endpush
@endsection