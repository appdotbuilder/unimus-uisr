<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Dataset
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $domain
 * @property string $task
 * @property string $license
 * @property string|null $doi
 * @property string $access_level
 * @property string $collaboration_type
 * @property string $status
 * @property array|null $keywords
 * @property array|null $contributors
 * @property string $version
 * @property int $download_count
 * @property int $citation_count
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DatasetFile> $files
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CurationReview> $reviews
 * @property-read \App\Models\DatasetFile|null $primaryFile
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCitationCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCollaborationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereContributors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereDoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereDownloadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereTask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset published()
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset byDomain(string $domain)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset byCollaborationType(string $type)
 * @method static \Database\Factories\DatasetFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Dataset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'domain',
        'task',
        'license',
        'doi',
        'access_level',
        'collaboration_type',
        'status',
        'keywords',
        'contributors',
        'version',
        'download_count',
        'citation_count',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'keywords' => 'array',
        'contributors' => 'array',
        'download_count' => 'integer',
        'citation_count' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the dataset.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files for the dataset.
     */
    public function files(): HasMany
    {
        return $this->hasMany(DatasetFile::class);
    }

    /**
     * Get the curation reviews for the dataset.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(CurationReview::class);
    }

    /**
     * Get the primary file for the dataset.
     */
    public function primaryFile()
    {
        return $this->hasOne(DatasetFile::class)->where('is_primary', true);
    }

    /**
     * Scope a query to only include published datasets.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to filter by domain.
     */
    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    /**
     * Scope a query to filter by collaboration type.
     */
    public function scopeByCollaborationType($query, string $type)
    {
        return $query->where('collaboration_type', $type);
    }
}