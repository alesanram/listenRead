<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table='reviews'; 

    protected $fillable = [
        'tittle','text','start'
    ];

    //1:N relationship
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function novel(){
        return $this->belongsTo(Novel::class);
    }

    //N:M relationship
    public function tags() {
        return $this->belongsToMany(Tag::class, 'review_tag');
    }
}
