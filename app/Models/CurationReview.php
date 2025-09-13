<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CurationReview
 *
 * @property int $id
 * @property int $dataset_id
 * @property int $reviewer_id
 * @property string $status
 * @property string|null $notes
 * @property array|null $checklist
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dataset $dataset
 * @property-read \App\Models\User $reviewer
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereChecklist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereDatasetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurationReview whereUpdatedAt($value)
 * @method static \Database\Factories\CurationReviewFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CurationReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'dataset_id',
        'reviewer_id',
        'status',
        'notes',
        'checklist',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'checklist' => 'array',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the dataset that is being reviewed.
     */
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    /**
     * Get the reviewer (curator) who performed the review.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}