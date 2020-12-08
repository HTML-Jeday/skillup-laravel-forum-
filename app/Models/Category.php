<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Category extends Model {

    use HasFactory;

    protected $table = 'categories';

    public function subcategories() {
        return $this->hasMany('App\Models\Subcategory');
    }

}
