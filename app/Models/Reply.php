<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    /**
     * 回复的话题
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * 回复的人
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
