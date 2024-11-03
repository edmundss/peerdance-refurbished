<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'spotify_token',
        'spotify_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function avatar()
    {
        return $this->hasOne('App\Models\Picture', 'parent_id')->where('parent_class', 'User')->orderBy('created_at', 'DESC');
    }

    public function cover()
    {
        return $this->hasOne('App\Models\Picture', 'parent_id')->where('parent_class', 'ProfileCover')->orderBy('created_at', 'DESC');
    }


    public function getAvatar($size)
    {
        return asset('image/avatars/' . $this->avatar->id . '/'. $size . '.jpg');

    }

    public function dances()
    {
        return $this->belongsToMany('App\Models\Dance');
    }

    public function steps()
    {
        return $this->belongsToMany('App\Models\Step');
    }

    public function combinations()
    {
        return $this->belongsToMany('App\Models\Combination');
    }

    public function routines()
    {
        return $this->belongsToMany('App\Models\Routine');
    }
}
