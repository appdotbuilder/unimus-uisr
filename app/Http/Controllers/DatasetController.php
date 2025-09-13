<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDatasetRequest;
use App\Http\Requests\UpdateDatasetRequest;
use App\Models\Dataset;
use App\Models\DatasetFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Dataset::with(['user.profile', 'primaryFile'])
            ->withCount(['files', 'reviews'])
            ->published()
            ->latest('published_at');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('domain', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status (for searching both published and under review)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to only published if no status filter
            $query->where('status', 'published');
        }

        // Filter by domain
        if ($request->filled('domain')) {
            $query->byDomain($request->domain);
        }

        // Filter by collaboration type
        if ($request->filled('collaboration_type')) {
            $query->byCollaborationType($request->collaboration_type);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest('published_at');
                    break;
                case 'title':
                    $query->orderBy('title');
                    break;
                case 'popular':
                    $query->orderByDesc('download_count');
                    break;
                default:
                    $query->latest('published_at');
            }
        }

        $perPage = $request->get('per_page', 10);
        $datasets = $query->paginate($perPage)->withQueryString();

        // Get available domains for filter
        $domains = Dataset::published()
            ->distinct()
            ->pluck('domain')
            ->sort()
            ->values();

        return view('datasets.index', [
            'datasets' => $datasets,
            'domains' => $domains,
            'filters' => $request->only(['search', 'domain', 'collaboration_type']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('datasets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatasetRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'draft';

        $dataset = Dataset::create($validated);

        // Handle file upload
        if ($request->hasFile('dataset_file')) {
            $this->handleFileUpload($request->file('dataset_file'), $dataset);
        }

        return redirect()->route('datasets.show', $dataset)
            ->with('success', 'Dataset created successfully. You can now submit it for review.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dataset $dataset)
    {
        $dataset->load(['user.profile', 'files', 'reviews']);
        $dataset->loadCount(['files', 'reviews']);

        // Increment view count if dataset is published and user is not the owner
        if ($dataset->status === 'published' && (!auth()->check() || auth()->id() !== $dataset->user_id)) {
            // Add views_count to fillable if not exists
            $dataset->increment('download_count'); // Using download_count as view count for now
        }

        return view('datasets.show', [
            'dataset' => $dataset,
            'canEdit' => auth()->check() && 
                       (auth()->id() === $dataset->user_id || auth()->user()->isAdmin()),
            'canReview' => auth()->check() && auth()->user()->isCurator(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dataset $dataset)
    {
        // Only allow editing by owner or admin, and only if not published
        if (auth()->id() !== $dataset->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($dataset->status === 'published') {
            return redirect()->route('datasets.show', $dataset)
                ->with('error', 'Published datasets cannot be edited.');
        }

        $dataset->load('files');

        return view('datasets.edit', [
            'dataset' => $dataset,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatasetRequest $request, Dataset $dataset)
    {
        // Only allow updating by owner or admin, and only if not published
        if (auth()->id() !== $dataset->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($dataset->status === 'published') {
            return redirect()->route('datasets.show', $dataset)
                ->with('error', 'Published datasets cannot be updated.');
        }

        $dataset->update($request->validated());

        // Handle new file upload
        if ($request->hasFile('dataset_file')) {
            $this->handleFileUpload($request->file('dataset_file'), $dataset);
        }

        return redirect()->route('datasets.show', $dataset)
            ->with('success', 'Dataset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dataset $dataset)
    {
        // Only allow deletion by owner or admin
        if (auth()->id() !== $dataset->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete associated files from storage
        foreach ($dataset->files as $file) {
            Storage::disk('local')->delete($file->path);
        }

        $dataset->delete();

        return redirect()->route('datasets.index')
            ->with('success', 'Dataset deleted successfully.');
    }

    /**
     * Handle file upload for a dataset.
     */
    protected function handleFileUpload($file, Dataset $dataset)
    {
        // Validate file
        $allowedExtensions = ['csv', 'json', 'arff'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new \InvalidArgumentException('Invalid file type. Only CSV, JSON, and ARFF files are allowed.');
        }

        if ($file->getSize() > 52428800) { // 50MB
            throw new \InvalidArgumentException('File size too large. Maximum size is 50MB.');
        }

        // Generate unique filename and path
        $filename = Str::uuid() . '.' . $extension;
        $path = 'datasets/' . $dataset->id . '/' . $filename;

        // Store file
        $file->storeAs('datasets/' . $dataset->id, $filename, 'local');

        // Create database record
        DatasetFile::create([
            'dataset_id' => $dataset->id,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'is_primary' => !$dataset->files()->exists(), // First file is primary
            'metadata' => $this->extractFileMetadata($file, $extension),
        ]);
    }

    /**
     * Extract metadata from uploaded file.
     */
    protected function extractFileMetadata($file, string $extension): array
    {
        $metadata = [
            'encoding' => 'UTF-8',
        ];

        try {
            if ($extension === 'csv') {
                $handle = fopen($file->getRealPath(), 'r');
                if ($handle) {
                    $firstRow = fgetcsv($handle);
                    $metadata['columns'] = $firstRow ? count($firstRow) : 0;
                    $metadata['delimiter'] = ',';
                    
                    // Count rows (sample first 1000 for performance)
                    $rowCount = 1; // Header row
                    while (($row = fgetcsv($handle)) && $rowCount < 1000) {
                        $rowCount++;
                    }
                    $metadata['rows'] = $rowCount;
                    fclose($handle);
                }
            }
        } catch (\Exception $e) {
            // If metadata extraction fails, continue without it
        }

        return $metadata;
    }
}