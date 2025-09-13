@extends('layouts.app')

@section('title', 'Submit Dataset - UISR')

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
                        <li class="breadcrumb-item active" aria-current="page">Submit New Dataset</li>
                    </ol>
                </nav>
                
                <h1 class="h2 mb-1">
                    <i class="bi bi-upload text-primary me-2"></i>
                    Submit New Dataset
                </h1>
                <p class="text-muted">Share your research data with the academic community</p>
            </div>

            <!-- Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('datasets.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Dataset Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="Enter a descriptive title for your dataset"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Choose a clear, descriptive title that researchers can easily understand
                            </div>
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
                                      placeholder="Provide a detailed description of your dataset, including methodology, data collection process, and potential applications..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Include methodology, data sources, collection period, and intended use cases
                            </div>
                        </div>

                        <!-- Domain -->
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
                                    <option value="computer_science" {{ old('domain') === 'computer_science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="engineering" {{ old('domain') === 'engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="medicine" {{ old('domain') === 'medicine' ? 'selected' : '' }}>Medicine & Health</option>
                                    <option value="business" {{ old('domain') === 'business' ? 'selected' : '' }}>Business & Economics</option>
                                    <option value="social_science" {{ old('domain') === 'social_science' ? 'selected' : '' }}>Social Sciences</option>
                                    <option value="education" {{ old('domain') === 'education' ? 'selected' : '' }}>Education</option>
                                    <option value="agriculture" {{ old('domain') === 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                    <option value="environmental" {{ old('domain') === 'environmental' ? 'selected' : '' }}>Environmental Sciences</option>
                                    <option value="other" {{ old('domain') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Collaboration Type -->
                            <div class="col-md-6">
                                <label for="collaboration_type" class="form-label fw-semibold">
                                    Collaboration Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('collaboration_type') is-invalid @enderror" 
                                        id="collaboration_type" 
                                        name="collaboration_type" 
                                        required>
                                    <option value="">Select Collaboration Type</option>
                                    <option value="local" {{ old('collaboration_type') === 'local' ? 'selected' : '' }}>Local (Within Institution)</option>
                                    <option value="national" {{ old('collaboration_type') === 'national' ? 'selected' : '' }}>National (Indonesia)</option>
                                    <option value="international" {{ old('collaboration_type') === 'international' ? 'selected' : '' }}>International</option>
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
                                   value="{{ old('keywords') }}" 
                                   placeholder="machine learning, artificial intelligence, data mining">
                            @error('keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-tags me-1"></i>
                                Separate keywords with commas. These help other researchers find your dataset
                            </div>
                        </div>

                        <!-- License -->
                        <div class="mb-4">
                            <label for="license" class="form-label fw-semibold">License</label>
                            <select class="form-select @error('license') is-invalid @enderror" 
                                    id="license" 
                                    name="license">
                                <option value="">Select License (Optional)</option>
                                <option value="CC0" {{ old('license') === 'CC0' ? 'selected' : '' }}>CC0 - Public Domain</option>
                                <option value="CC BY" {{ old('license') === 'CC BY' ? 'selected' : '' }}>CC BY - Attribution</option>
                                <option value="CC BY-SA" {{ old('license') === 'CC BY-SA' ? 'selected' : '' }}>CC BY-SA - Attribution-ShareAlike</option>
                                <option value="MIT" {{ old('license') === 'MIT' ? 'selected' : '' }}>MIT License</option>
                                <option value="Apache 2.0" {{ old('license') === 'Apache 2.0' ? 'selected' : '' }}>Apache 2.0</option>
                                <option value="Custom" {{ old('license') === 'Custom' ? 'selected' : '' }}>Custom License</option>
                            </select>
                            @error('license')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="dataset_file" class="form-label fw-semibold">
                                Dataset File <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control @error('dataset_file') is-invalid @enderror" 
                                   id="dataset_file" 
                                   name="dataset_file" 
                                   accept=".csv,.json,.arff"
                                   required>
                            @error('dataset_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i>
                                Supported formats: CSV, JSON, ARFF. Maximum file size: 50MB
                            </div>
                        </div>

                        <!-- Submission Guidelines -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-lightbulb me-2"></i>
                                Submission Guidelines
                            </h6>
                            <ul class="mb-0 small">
                                <li>Ensure your data is properly cleaned and anonymized if necessary</li>
                                <li>Include clear column headers for CSV files</li>
                                <li>Provide comprehensive documentation in the description field</li>
                                <li>Your dataset will be reviewed before publication</li>
                                <li>You can edit your dataset before it's published</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i>
                                Submit Dataset
                            </button>
                            <a href="{{ route('datasets.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card border-0 bg-light mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-question-circle text-info me-2"></i>
                        Need Help?
                    </h6>
                    <p class="card-text small mb-2">
                        If you have questions about dataset submission, please refer to our guidelines or contact the curation team.
                    </p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info" onclick="showHelpModal()">
                            <i class="bi bi-book me-1"></i>
                            View Guidelines
                        </button>
                        <a href="mailto:support@uisr.unimus.ac.id" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-envelope me-1"></i>
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showHelpModal() {
    alert('Guidelines:\n\n• Use descriptive titles and comprehensive descriptions\n• Clean and anonymize sensitive data\n• Choose appropriate licenses\n• Follow academic data sharing standards');
}
</script>
@endpush
@endsection