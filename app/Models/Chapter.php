<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $table='chapters';

    protected $fillable = [
        'name','number',
        'is_publish','date_publish',
        'type', 'rute',
        'text','novel_id',
        'creator_id','author_id'
    ];


    //1: N relationship
    public function novel(){
        return $this->belongsTo(Novel::class);
    }
}
