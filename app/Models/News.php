<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class News extends Model
{
    use HasFactory, SoftDeletes, Sluggable, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'active',
        'content',
        'published_at',
        'administrator_id',
        'image_thumb',
    ];

    public function shouldBeSearchable()
    {
        if ($this->active == 1 && $this->deleted_at != '') {
            return true;
        }
        return false;
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', '1');
    }

    public function publish()
    {
        $this->published_at = now();
        $this->save();
    }

    public function getDateAndHourVNAttribute()
    {
        return date_format($this->created_at, "d/m/Y H:i");
    }

    public function getDeletedAtVNAttribute()
    {
        return date_format($this->deleted_at, "d-m-Y H:i:s");
    }

    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }
}
