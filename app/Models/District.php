<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $casts = ['id' => 'string'];

    public function cities() {
        return $this->belongsTo(City::class);
    }

    public function communes() {
        return $this->hasMany(Commune::class);
    }

}
