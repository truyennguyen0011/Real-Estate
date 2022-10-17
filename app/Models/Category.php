<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'active',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', '1');
    }
 
    public function getCreatedAtVNAttribute()
    {
        return date_format($this->created_at,"H:i:s - d/m/Y");
    }

    public function getDeletedAtVNAttribute()
    {
        return date_format($this->deleted_at,"d-m-Y H:i:s");
    }
   
    public function getUpdatedAtVNAttribute()
    {
        return date_format($this->updated_at,"H:i:s - d/m/Y");
    }
}
