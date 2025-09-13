@extends('layouts.app')

@section('title', 'Edit Dataset - UISR')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('datasets.index') }}">Datasets</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('datasets.show', $dataset) }}">{{ Str::limit($dataset->title, 30) }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
                
                <h1 class="h2 mb-1">
                    <i class="bi bi-pencil text-warning me-2"></i>
                    Edit Dataset
                </h1>
                <p class="text-muted">Update your dataset information and files</p>
            </div>

            <!-- Status Alert -->
            @if($dataset->status !== 'draft')
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Note:</strong> This dataset is currently {{ $dataset->status }}. 
                Changes may require additional review before being published.
            </div>
            @endif

            <!-- Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('datasets.update', $dataset) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Dataset Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $dataset->title) }}" 
                                   placeholder="Enter a descriptive title for your dataset"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="6"
                                      placeholder="Provide a detailed description of your dataset..."
                                      required>{{ old('description', $dataset->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Domain and Collaboration Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="domain" class="form-label fw-semibold">
                                    Research Domain <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('domain') is-invalid @enderror" 
                                        id="domain" 
                                        name="domain" 
                                        required>
                                    <option value="">Select Research Domain</option>
                                    <option value="computer_science" {{ old('domain', $dataset->domain) === 'computer_science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="engineering" {{ old('domain', $dataset->domain) === 'engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="medicine" {{ old('domain', $dataset->domain) === 'medicine' ? 'selected' : '' }}>Medicine & Health</option>
                                    <option value="business" {{ old('domain', $dataset->domain) === 'business' ? 'selected' : '' }}>Business & Economics</option>
                                    <option value="social_science" {{ old('domain', $dataset->domain) === 'social_science' ? 'selected' : '' }}>Social Sciences</option>
                                    <option value="education" {{ old('domain', $dataset->domain) === 'education' ? 'selected' : '' }}>Education</option>
                                    <option value="agriculture" {{ old('domain', $dataset->domain) === 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                    <option value="environmental" {{ old('domain', $dataset->domain) === 'environmental' ? 'selected' : '' }}>Environmental Sciences</option>
                                    <option value="other" {{ old('domain', $dataset->domain) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="collaboration_type" class="form-label fw-semibold">
                                    Collaboration Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('collaboration_type') is-invalid @enderror" 
                                        id="collaboration_type" 
                                        name="collaboration_type" 
                                        required>
                                    <option value="">Select Collaboration Type</option>
                                    <option value="local" {{ old('collaboration_type', $dataset->collaboration_type) === 'local' ? 'selected' : '' }}>Local (Within Institution)</option>
                                    <option value="national" {{ old('collaboration_type', $dataset->collaboration_type) === 'national' ? 'selected' : '' }}>National (Indonesia)</option>
                                    <option value="international" {{ old('collaboration_type', $dataset->collaboration_type) === 'international' ? 'selected' : '' }}>International</option>
                                </select>
                                @error('collaboration_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Keywords -->
                        <div class="mb-4">
                            <label for="keywords" class="form-label fw-semibold">Keywords</label>
                            <input type="text" 
                                   class="form-control @error('keywords') is-invalid @enderror" 
                                   id="keywords" 
                                   name="keywords" 
                                   value="{{ old('keywords', is_array($dataset->keywords) ? implode(', ', $dataset->keywords) : $dataset->keywords) }}" 
                                   placeholder="machine learning, artificial intelligence, data mining">
                            @error('keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-tags me-1"></i>
                                Separate keywords with commas
                            </div>
                        </div>

                        <!-- License -->
                        <div class="mb-4">
                            <label for="license" class="form-label fw-semibold">License</label>
                            <select class="form-select @error('license') is-invalid @enderror" 
                                    id="license" 
                                    name="license">
                                <option value="">Select License (Optional)</option>
                                <option value="CC0" {{ old('license', $dataset->license) === 'CC0' ? 'selected' : '' }}>CC0 - Public Domain</option>
                                <option value="CC BY" {{ old('license', $dataset->license) === 'CC BY' ? 'selected' : '' }}>CC BY - Attribution</option>
                                <option value="CC BY-SA" {{ old('license', $dataset->license) === 'CC BY-SA' ? 'selected' : '' }}>CC BY-SA - Attribution-ShareAlike</option>
                                <option value="MIT" {{ old('license', $dataset->license) === 'MIT' ? 'selected' : '' }}>MIT License</option>
                                <option value="Apache 2.0" {{ old('license', $dataset->license) === 'Apache 2.0' ? 'selected' : '' }}>Apache 2.0</option>
                                <option value="Custom" {{ old('license', $dataset->license) === 'Custom' ? 'selected' : '' }}>Custom License</option>
                            </select>
                            @error('license')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Files -->
                        @if($dataset->files->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Current Files</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @foreach($dataset->files as $file)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <i class="bi bi-file-earmark me-2"></i>
                                            <span>{{ $file->original_name ?? $file->filename }}</span>
                                            @if($file->is_primary)
                                                <span class="badge bg-primary ms-2">Primary</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            {{ $file->size_formatted ?? 'Unknown size' }}
                                        </small>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- New File Upload -->
                        <div class="mb-4">
                            <label for="dataset_file" class="form-label fw-semibold">
                                Upload New File (Optional)
                            </label>
                            <input type="file" 
                                   class="form-control @error('dataset_file') is-invalid @enderror" 
                                   id="dataset_file" 
                                   name="dataset_file" 
                                   accept=".csv,.json,.arff">
                            @error('dataset_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Leave empty to keep existing files. Uploading a new file will replace the current primary file.
                            </div>
                        </div>

                        <!-- Update Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle me-1"></i>
                                Update Dataset
                            </button>
                            <a href="{{ route('datasets.show', $dataset) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancel
                            </a>
                            
                            @if($dataset->status === 'draft')
                            <button type="submit" name="submit_for_review" value="1" class="btn btn-success ms-auto">
                                <i class="bi bi-send me-1"></i>
                                Submit for Review
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            @if($dataset->status === 'draft')
            <div class="card border-danger mt-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Once deleted, this dataset and all associated files will be permanently removed and cannot be recovered.
                    </p>
                    <form method="POST" action="{{ route('datasets.destroy', $dataset) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this dataset? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>
                            Delete Dataset
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection