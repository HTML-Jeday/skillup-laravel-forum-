<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Message Model
 *
 * @property int $id
 * @property int $author
 * @property string $text
 * @property int $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \App\Models\Topic $topic
 * @property-read \App\Models\User $user
 */
class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'author',
        'text',
        'parent_id'
    ];

    /**
     * Get the topic that the message belongs to.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    /**
     * Get the user that authored the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author');
    }
}
