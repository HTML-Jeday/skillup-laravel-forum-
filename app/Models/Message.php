<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $author
 * @property string $text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $parent_id
 */
class Message extends Model {

    use HasFactory;

    public function topic() {
        return $this->belongsTo(Topic::class);
    }

}
