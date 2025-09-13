<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display accreditation reports.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isCurator()) {
            abort(403, 'Access denied. Only curators and admins can view reports.');
        }

        $year = $request->get('year', Carbon::now()->year);

        // Datasets per year
        $datasetsPerYear = Dataset::published()
            ->selectRaw('YEAR(published_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Datasets per lecturer for selected year
        $datasetsPerLecturer = Dataset::join('users', 'datasets.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('profiles.type', 'lecturer')
            ->where('datasets.status', 'published')
            ->whereYear('datasets.published_at', $year)
            ->select(
                'profiles.first_name',
                'profiles.last_name',
                'profiles.department',
                'users.email',
                DB::raw('COUNT(datasets.id) as dataset_count')
            )
            ->groupBy('users.id', 'profiles.first_name', 'profiles.last_name', 'profiles.department', 'users.email')
            ->orderBy('dataset_count', 'desc')
            ->get();

        // Student involvement
        $studentInvolvement = Dataset::join('users', 'datasets.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('profiles.type', 'student')
            ->where('datasets.status', 'published')
            ->whereYear('datasets.published_at', $year)
            ->count();

        // Collaboration statistics
        $collaborationStats = Dataset::published()
            ->whereYear('published_at', $year)
            ->selectRaw('collaboration_type, COUNT(*) as count')
            ->groupBy('collaboration_type')
            ->get()
            ->pluck('count', 'collaboration_type');

        // Domain distribution
        $domainStats = Dataset::published()
            ->whereYear('published_at', $year)
            ->selectRaw('domain, COUNT(*) as count')
            ->groupBy('domain')
            ->orderBy('count', 'desc')
            ->get();

        return Inertia::render('reports/index', [
            'year' => $year,
            'datasetsPerYear' => $datasetsPerYear,
            'datasetsPerLecturer' => $datasetsPerLecturer,
            'studentInvolvement' => $studentInvolvement,
            'collaborationStats' => $collaborationStats,
            'domainStats' => $domainStats,
            'totalDatasets' => Dataset::published()->whereYear('published_at', $year)->count(),
        ]);
    }

    /**
     * Export reports to CSV.
     */
    public function create(Request $request)
    {
        if (!auth()->user()->isCurator()) {
            abort(403, 'Access denied.');
        }

        $year = $request->get('year', Carbon::now()->year);
        $type = $request->get('type', 'datasets');

        $filename = "uisr_report_{$type}_{$year}.csv";
        
        return response()->streamDownload(function () use ($type, $year) {
            $this->generateCsvReport($type, $year);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Generate CSV report.
     */
    protected function generateCsvReport(string $type, int $year): void
    {
        $output = fopen('php://output', 'w');

        switch ($type) {
            case 'lecturers':
                fputcsv($output, ['Name', 'Email', 'Department', 'Datasets Count', 'Year']);
                
                $data = Dataset::join('users', 'datasets.user_id', '=', 'users.id')
                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                    ->where('profiles.type', 'lecturer')
                    ->where('datasets.status', 'published')
                    ->whereYear('datasets.published_at', $year)
                    ->select(
                        'profiles.first_name',
                        'profiles.last_name',
                        'users.email',
                        'profiles.department',
                        DB::raw('COUNT(datasets.id) as dataset_count')
                    )
                    ->groupBy('users.id', 'profiles.first_name', 'profiles.last_name', 'users.email', 'profiles.department')
                    ->get();

                foreach ($data as $row) {
                    fputcsv($output, [
                        ($row->first_name ?? '') . ' ' . ($row->last_name ?? ''),
                        $row->email ?? '',
                        $row->department ?? '',
                        $row->dataset_count ?? 0,
                        $year,
                    ]);
                }
                break;

            case 'datasets':
                fputcsv($output, ['Title', 'Author', 'Domain', 'Collaboration Type', 'Published Date', 'Downloads', 'Citations']);
                
                $datasets = Dataset::with(['user.profile'])
                    ->published()
                    ->whereYear('published_at', $year)
                    ->get();

                foreach ($datasets as $dataset) {
                    $authorName = $dataset->user->name;
                    if ($dataset->user->profile) {
                        $profile = $dataset->user->profile;
                        $authorName = ($profile->first_name ?? '') . ' ' . ($profile->last_name ?? '');
                    }
                    
                    fputcsv($output, [
                        $dataset->title,
                        $authorName,
                        $dataset->domain,
                        ucfirst($dataset->collaboration_type),
                        $dataset->published_at?->format('Y-m-d'),
                        $dataset->download_count,
                        $dataset->citation_count,
                    ]);
                }
                break;
        }

        fclose($output);
    }
}