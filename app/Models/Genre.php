<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $table='genres';

    protected $fillable = [
        'name'
    ];

    public function novels() {
        return $this->belongsToMany(Novel::class, 'novel_genre');
    }
}

