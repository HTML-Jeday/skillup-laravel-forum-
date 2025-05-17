<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Enums\TopicStatus;

/**
 * Topic Model
 *
 * @property int $id
 * @property string $title
 * @property int $author
 * @property int $parent_id
 * @property string $text
 * @property TopicStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Subcategory $subcategory
 */
class Topic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'parent_id',
        'text',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => TopicStatus::class,
    ];

    /**
     * Get the messages for the topic.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    /**
     * Get the user that authored the topic.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author');
    }

    /**
     * Get the subcategory that the topic belongs to.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'parent_id');
    }
}
