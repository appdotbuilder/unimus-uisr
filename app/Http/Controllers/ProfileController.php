<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();
        $user->load(['profile', 'datasets' => function ($query) {
            $query->published()->with('primaryFile')->latest('published_at');
        }]);

        return view('profiles.show', [
            'user' => $user,
            'isOwnProfile' => auth()->check() && auth()->id() === $user->id,
        ]);
    }

    /**
     * Show the form for creating a new profile.
     */
    public function create()
    {
        if (auth()->user()->profile) {
            return redirect()->route('profile.show')
                ->with('info', 'You already have a profile.');
        }

        return view('profiles.create');
    }

    /**
     * Store a newly created profile.
     */
    public function store(StoreProfileRequest $request)
    {
        if (auth()->user()->profile) {
            return redirect()->route('profile.show')
                ->with('error', 'You already have a profile.');
        }

        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        Profile::create($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile created successfully.');
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $profile = auth()->user()->profile;
        
        if (!$profile) {
            return redirect()->route('profile.create')
                ->with('info', 'Please create your profile first.');
        }

        return view('profiles.edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $profile = auth()->user()->profile;

        if (!$profile) {
            return redirect()->route('profile.create')
                ->with('error', 'Please create your profile first.');
        }

        $profile->update($request->validated());

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}