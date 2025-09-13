<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\DatasetFile
 *
 * @property int $id
 * @property int $dataset_id
 * @property string $filename
 * @property string $original_filename
 * @property string $path
 * @property int $size
 * @property string $mime_type
 * @property string $extension
 * @property array|null $metadata
 * @property bool $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dataset $dataset
 * @property-read string $formatted_size
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereDatasetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatasetFile whereUpdatedAt($value)
 * @method static \Database\Factories\DatasetFileFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class DatasetFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'dataset_id',
        'filename',
        'original_filename',
        'path',
        'size',
        'mime_type',
        'extension',
        'metadata',
        'is_primary',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'metadata' => 'array',
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the dataset that owns the file.
     */
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}