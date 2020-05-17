<?php

namespace App;

use App\Post;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified',
        'verification_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setEmailAttribute($email)
    {
      $this->attributes['email'] = strtolower($email);
    }

    public function isVerified()
    {
      return $this->verified == User::VERIFIED_USER;
    }

    public static function generateVerificationCode()
    {
      return Str::random(40);
    }

    public function posts()
    {
      return $this->hasMany(Post::class);
    }
}
