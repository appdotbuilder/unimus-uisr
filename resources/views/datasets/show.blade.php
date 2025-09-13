@extends('layouts.app')

@section('title', $dataset->title . ' - UISR')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('datasets.index') }}">Datasets</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($dataset->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Dataset Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-start mb-3">
                        @php
                            $statusClass = match($dataset->status) {
                                'published' => 'bg-success',
                                'under_review' => 'bg-warning text-dark',
                                'draft' => 'bg-secondary',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} me-2">
                            {{ ucfirst(str_replace('_', ' ', $dataset->status)) }}
                        </span>
                        <small class="text-muted">
                            Published on {{ $dataset->created_at->format('F j, Y') }}
                        </small>
                    </div>

                    <h1 class="display-5 fw-bold mb-3">{{ $dataset->title }}</h1>
                    
                    <!-- Author Information -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1">
                                <a href="{{ route('profiles.show', $dataset->user) }}" 
                                   class="text-decoration-none">
                                    {{ $dataset->user->name }}
                                </a>
                            </h6>
                            @if($dataset->user->profile && $dataset->user->profile->affiliation)
                                <small class="text-muted">{{ $dataset->user->profile->affiliation }}</small>
                            @endif
                            @if($dataset->user->profile && $dataset->user->profile->position)
                                <br><small class="text-muted">{{ $dataset->user->profile->position }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Action Buttons -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Actions</h6>
                            
                            @auth
                                @if(auth()->id() === $dataset->user_id)
                                    <div class="d-grid gap-2 mb-3">
                                        <a href="{{ route('datasets.edit', $dataset) }}" class="btn btn-primary">
                                            <i class="bi bi-pencil me-1"></i>
                                            Edit Dataset
                                        </a>
                                    </div>
                                @endif
                            @endauth

                            @if($dataset->status === 'published')
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success" onclick="downloadDataset()">
                                        <i class="bi bi-download me-1"></i>
                                        Download Dataset
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="showCitationModal()">
                                        <i class="bi bi-quote me-1"></i>
                                        Cite Dataset
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="shareDataset()">
                                        <i class="bi bi-share me-1"></i>
                                        Share
                                    </button>
                                </div>
                            @endif

                            <!-- Dataset Statistics -->
                            <hr>
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="small text-muted">Views</div>
                                    <div class="fw-bold">{{ $dataset->views_count ?? 0 }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Downloads</div>
                                    <div class="fw-bold">{{ $dataset->downloads_count ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dataset Content -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Description -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-file-text text-primary me-2"></i>
                                Description
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($dataset->description)
                                <div class="prose">
                                    {!! nl2br(e($dataset->description)) !!}
                                </div>
                            @else
                                <p class="text-muted fst-italic">No description provided.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Files -->
                    @if($dataset->files->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-folder-fill text-primary me-2"></i>
                                Files ({{ $dataset->files->count() }})
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Size</th>
                                            <th>Type</th>
                                            <th width="120">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataset->files as $file)
                                        <tr>
                                            <td>
                                                <i class="bi bi-file-earmark me-2"></i>
                                                {{ $file->original_name }}
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $file->size_formatted ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ strtoupper($file->extension ?? 'Unknown') }}</span>
                                            </td>
                                            <td>
                                                @if($dataset->status === 'published')
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="downloadFile('{{ $file->id }}')">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                @else
                                                    <small class="text-muted">Preview only</small>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Reviews -->
                    @if($dataset->reviews->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-chat-square-dots text-primary me-2"></i>
                                Reviews ({{ $dataset->reviews->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($dataset->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-1">{{ $review->reviewer_name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('M j, Y') }}</small>
                                </div>
                                @if($review->status)
                                    @php
                                        $reviewStatusClass = match($review->status) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            'revision_requested' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $reviewStatusClass }} mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                    </span>
                                @endif
                                @if($review->comments)
                                    <p class="mb-0">{{ $review->comments }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Dataset Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                Dataset Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <dl class="row small">
                                <dt class="col-sm-5">Created:</dt>
                                <dd class="col-sm-7">{{ $dataset->created_at->format('M j, Y') }}</dd>
                                
                                <dt class="col-sm-5">Updated:</dt>
                                <dd class="col-sm-7">{{ $dataset->updated_at->format('M j, Y') }}</dd>
                                
                                @if($dataset->license)
                                <dt class="col-sm-5">License:</dt>
                                <dd class="col-sm-7">{{ $dataset->license }}</dd>
                                @endif
                                
                                @if($dataset->doi)
                                <dt class="col-sm-5">DOI:</dt>
                                <dd class="col-sm-7">
                                    <a href="https://doi.org/{{ $dataset->doi }}" target="_blank">
                                        {{ $dataset->doi }}
                                    </a>
                                </dd>
                                @endif

                                <dt class="col-sm-5">Files:</dt>
                                <dd class="col-sm-7">{{ $dataset->files->count() }} files</dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Keywords -->
                    @if($dataset->keywords)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-tags text-primary me-2"></i>
                                Keywords
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach(explode(',', $dataset->keywords) as $keyword)
                                @if(trim($keyword))
                                <span class="badge bg-light text-dark me-1 mb-2">{{ trim($keyword) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Related Datasets -->
                    @if(isset($relatedDatasets) && $relatedDatasets->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-collection text-primary me-2"></i>
                                Related Datasets
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($relatedDatasets as $related)
                            <div class="mb-3">
                                <a href="{{ route('datasets.show', $related) }}" 
                                   class="text-decoration-none fw-semibold">
                                    {{ Str::limit($related->title, 60) }}
                                </a>
                                <br>
                                <small class="text-muted">by {{ $related->user->name }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Citation Modal -->
<div class="modal fade" id="citationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-quote me-2"></i>
                    Cite This Dataset
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#apa-tab">APA Format</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ieee-tab">IEEE Format</button>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="apa-tab">
                        <div class="bg-light p-3 rounded">
                            <code id="apa-citation">
                                {{ $dataset->user->name }} ({{ $dataset->created_at->format('Y') }}). 
                                <em>{{ $dataset->title }}</em>. 
                                UISR Repository. 
                                @if($dataset->doi)https://doi.org/{{ $dataset->doi }}@else{{ route('datasets.show', $dataset) }}@endif
                            </code>
                        </div>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="copyCitation('apa')">
                            <i class="bi bi-clipboard me-1"></i>Copy APA Citation
                        </button>
                    </div>
                    <div class="tab-pane fade" id="ieee-tab">
                        <div class="bg-light p-3 rounded">
                            <code id="ieee-citation">
                                {{ $dataset->user->name }}, "{{ $dataset->title }}," 
                                UISR Repository, {{ $dataset->created_at->format('Y') }}. 
                                [Online]. Available: @if($dataset->doi)https://doi.org/{{ $dataset->doi }}@else{{ route('datasets.show', $dataset) }}@endif
                            </code>
                        </div>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="copyCitation('ieee')">
                            <i class="bi bi-clipboard me-1"></i>Copy IEEE Citation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showCitationModal() {
    new bootstrap.Modal(document.getElementById('citationModal')).show();
}

function copyCitation(format) {
    const citation = document.getElementById(format + '-citation').textContent;
    navigator.clipboard.writeText(citation).then(function() {
        alert('Citation copied to clipboard!');
    });
}

function downloadDataset() {
    // Implement dataset download functionality
    alert('Dataset download initiated!');
}

function downloadFile(fileId) {
    // Implement file download functionality
    alert('File download initiated!');
}

function shareDataset() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        alert('Dataset URL copied to clipboard!');
    });
}
</script>
@endpush
@endsection