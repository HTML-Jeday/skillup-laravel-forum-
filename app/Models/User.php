<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App/Models
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string role
 * @property boolean $verified
 * @property boolean $gender
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
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics() {
        return $this->hasMany(Topic::class, 'author');
    }

    public function messages() {
        return $this->hasMany(Message::class, 'author');
    }

}
