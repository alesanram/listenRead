<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table='users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'aboutMe',
        'fondo',
        'role_id'
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
    ];

    //1:N relationship
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function novels(){
        return $this->hasMany(Novel::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function chapters(){
        return $this->hasMany(chapter::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    //N:M relationship

    public function novelsCo() {
        return $this->belongsToMany(Novel::class, 'user_novel');
    }
}
