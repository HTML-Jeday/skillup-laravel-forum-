<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App/Models
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Role $role
 * @property boolean $verified
 * @property Gender $gender
 * @property string $FirstName
 * @property string $LastName
 * @property string $avatar
 * @property CARBON $created_at
 * @property CARBON $updated_at
 */
class User extends Authenticatable {

    use HasFactory,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
//        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'email_verified_at' => 'datetime',
        'gender' => Gender::class,
        'role' => Role::class
    ];

    public function topics() : HasMany
    {
        return $this->hasMany(Topic::class, 'author');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'author');
    }

}
