<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Sluggable, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'active',
        'address',
        'price',
        'area',
        'name_seller',
        'email_seller',
        'phone_seller',
        'direction',
        'description',
        'thumb_image',
        'category_id',
        'administrator_id',
        'city_id',
        'district_id',
        'commune_id',
        'youtube_id',
        'published_at',
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
            'description' => $this->description,
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Images::class);
    }

    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }
}
