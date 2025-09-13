<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get user's own datasets
        $userDatasets = Dataset::where('user_id', $user->id)
            ->withCount(['files', 'reviews'])
            ->latest()
            ->take(5)
            ->get();

        // Overall statistics (visible to curators and admins)
        $statistics = null;
        if ($user->isCurator()) {
            $statistics = (object) $this->getOverallStatistics($request);
        }

        // Recent activity
        $recentDatasets = Dataset::with(['user.profile', 'primaryFile'])
            ->published()
            ->latest('published_at')
            ->take(10)
            ->get();

        return view('dashboard', [
            'userDatasets' => $userDatasets,
            'statistics' => $statistics,
            'recentDatasets' => $recentDatasets,
            'canViewReports' => $user->isCurator(),
        ]);
    }



    /**
     * Get overall statistics for curators/admins.
     */
    protected function getOverallStatistics(Request $request): array
    {
        return [
            'totalDatasets' => Dataset::published()->count(),
            'totalUsers' => User::count(),
            'totalLecturers' => User::join('profiles', 'users.id', '=', 'profiles.user_id')
                                   ->where('profiles.type', 'lecturer')->count(),
            'totalStudents' => User::join('profiles', 'users.id', '=', 'profiles.user_id')
                                  ->where('profiles.type', 'student')->count(),
            'datasetsThisMonth' => Dataset::published()
                                          ->whereMonth('published_at', Carbon::now()->month)
                                          ->whereYear('published_at', Carbon::now()->year)
                                          ->count(),
            'pendingReviews' => Dataset::where('status', 'under_review')->count(),
        ];
    }
}