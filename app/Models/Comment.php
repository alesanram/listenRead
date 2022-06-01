<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table='comments';

    protected $fillable = [
        'text','user_id','chapter_id'
    ];

    //1:N relationship
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }

}
