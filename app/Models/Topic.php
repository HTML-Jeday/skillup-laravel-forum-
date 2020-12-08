<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property int    $author
 * @property int $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property boolean    $opened
 */
class Topic extends Model {

    use HasFactory;

    public function messages() {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subcategory() {
        return $this->belongsTo('App\Models\Subcategory', 'id');
    }

}
