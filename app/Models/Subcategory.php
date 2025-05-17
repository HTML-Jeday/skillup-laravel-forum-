<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Subcategory Model
 *
 * @property int $id
 * @property string $title
 * @property int $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Topic[] $topics
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 */
class Subcategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subcategories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'parent_id'
    ];

    /**
     * Get the category that the subcategory belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the topics for the subcategory.
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'parent_id');
    }

    /**
     * Get the messages for the subcategory.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
