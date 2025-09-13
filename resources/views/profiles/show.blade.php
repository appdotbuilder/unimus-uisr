@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Profile Header -->
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-person-fill text-white" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h1 class="h3 mb-1">{{ $user->name }}</h1>
                                    <p class="text-muted mb-2">{{ $user->email }}</p>
                                    @if($user->profile)
                                        @if($user->profile->position)
                                            <p class="mb-1"><strong>Position:</strong> {{ $user->profile->position }}</p>
                                        @endif
                                        @if($user->profile->affiliation)
                                            <p class="mb-1"><strong>Affiliation:</strong> {{ $user->profile->affiliation }}</p>
                                        @endif
                                        @if($user->profile->research_interests)
                                            <div class="mt-2">
                                                @foreach(explode(',', $user->profile->research_interests) as $interest)
                                                    @if(trim($interest))
                                                    <span class="badge bg-light text-dark me-1">{{ trim($interest) }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-muted fst-italic">No profile information available</p>
                                    @endif
                                </div>
                                @if($isOwnProfile)
                                <div class="col-auto">
                                    @if($user->profile)
                                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i>
                                            Edit Profile
                                        </a>
                                    @else
                                        <a href="{{ route('profile.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Create Profile
                                        </a>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            @if($user->profile)
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-badge text-primary me-2"></i>
                                Profile Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                @if($user->profile->bio)
                                <dt class="col-sm-3">Biography:</dt>
                                <dd class="col-sm-9">{{ $user->profile->bio }}</dd>
                                @endif

                                @if($user->profile->orcid)
                                <dt class="col-sm-3">ORCID:</dt>
                                <dd class="col-sm-9">
                                    <a href="https://orcid.org/{{ $user->profile->orcid }}" target="_blank">
                                        {{ $user->profile->orcid }}
                                    </a>
                                </dd>
                                @endif

                                @if($user->profile->google_scholar)
                                <dt class="col-sm-3">Google Scholar:</dt>
                                <dd class="col-sm-9">
                                    <a href="{{ $user->profile->google_scholar }}" target="_blank">
                                        View Profile
                                    </a>
                                </dd>
                                @endif

                                @if($user->profile->website)
                                <dt class="col-sm-3">Website:</dt>
                                <dd class="col-sm-9">
                                    <a href="{{ $user->profile->website }}" target="_blank">
                                        {{ $user->profile->website }}
                                    </a>
                                </dd>
                                @endif

                                @if($user->role)
                                <dt class="col-sm-3">Role:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                </dd>
                                @endif

                                <dt class="col-sm-3">Member since:</dt>
                                <dd class="col-sm-9">{{ $user->created_at->format('F Y') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Published Datasets -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-database text-primary me-2"></i>
                                Published Datasets ({{ $user->datasets->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->datasets->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($user->datasets as $dataset)
                                    <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('datasets.show', $dataset) }}" 
                                                       class="text-decoration-none text-primary">
                                                        {{ $dataset->title }}
                                                    </a>
                                                </h6>
                                                @if($dataset->description)
                                                <p class="mb-2 text-muted">
                                                    {{ Str::limit($dataset->description, 150) }}
                                                </p>
                                                @endif
                                                <div class="d-flex align-items-center gap-3 text-muted small">
                                                    <span>
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        {{ $dataset->published_at ? $dataset->published_at->format('M j, Y') : $dataset->created_at->format('M j, Y') }}
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-tag me-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $dataset->domain)) }}
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-download me-1"></i>
                                                        {{ $dataset->download_count }} downloads
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-end">
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
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="text-muted mb-2">
                                        <i class="bi bi-database" style="font-size: 3rem;"></i>
                                    </div>
                                    <p class="text-muted">
                                        {{ $isOwnProfile ? 'You haven\'t published any datasets yet.' : 'No published datasets yet.' }}
                                    </p>
                                    @if($isOwnProfile)
                                        <a href="{{ route('datasets.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Submit Your First Dataset
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection