<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property int $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Subcategory extends Model {

    use HasFactory;

    protected $table = 'subcategories';

    public function category() {
        return $this->belongsTo('App\Models\Category');
    }

    public function topics() {
        return $this->hasMany('App\Models\Topic', 'parent_id');
    }

    public function messages() {
        return $this->hasMany('App\Models\Message', 'parent_id');
    }

}
