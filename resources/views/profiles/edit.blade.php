@extends('layouts.app')

@section('title', 'Edit Profile - UISR')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Your Profile</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Bio -->
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="4"
                                      placeholder="Tell us about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', $profile->position) }}"
                                   placeholder="e.g., Assistant Professor, Graduate Student">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Affiliation -->
                        <div class="mb-3">
                            <label for="affiliation" class="form-label">Affiliation</label>
                            <input type="text" class="form-control @error('affiliation') is-invalid @enderror" 
                                   id="affiliation" name="affiliation" value="{{ old('affiliation', $profile->affiliation) }}"
                                   placeholder="e.g., Universitas Muhammadiyah Semarang">
                            @error('affiliation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Research Interests -->
                        <div class="mb-3">
                            <label for="research_interests" class="form-label">Research Interests</label>
                            <input type="text" class="form-control @error('research_interests') is-invalid @enderror" 
                                   id="research_interests" name="research_interests" value="{{ old('research_interests', $profile->research_interests) }}"
                                   placeholder="e.g., Machine Learning, Data Mining, AI">
                            @error('research_interests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Separate multiple interests with commas</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection