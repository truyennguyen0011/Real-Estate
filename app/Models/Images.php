<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'folder',
        'post_id',
        'new_id',
    ];

    public function posts() {
        return $this->belongsTo(Post::class);
    }
}
