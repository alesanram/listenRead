<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    use HasFactory;

    protected $table='novels';

    protected $fillable = [
        'title','description','portada','user_id'
    ];


    //1:N relationship
    public function chapters(){
        return $this->hasMany(chapter::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    //N:M relationship
    public function usersCo() {
        return $this->belongsToMany(User::class, 'user_novel');
    }

    public function genres() {
        return $this->belongsToMany(Genre::class, 'novel_genre');
    }
}
