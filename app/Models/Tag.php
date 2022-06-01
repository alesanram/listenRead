<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table='tags';

    protected $fillable = [
        'name'
    ];

    //N:M relationship
    public function reviews() {
        return $this->belongsToMany(Review::class, 'review_tag');
    }
}
