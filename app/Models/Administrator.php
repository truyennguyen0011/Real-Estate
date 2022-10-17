<?php

namespace App\Models;

use App\Enums\AdminRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrator extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = 'administrator';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'active',
        'role',
        'avatar',
        'about_me',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getRoleNameAttribute()
    {
        return AdminRoleEnum::getKeys((int) $this->role)[0];
    }

    public function getDeletedAtVNAttribute()
    {
        return date_format($this->deleted_at,"d-m-Y H:i:s");
    }

    public function getCreatedAtVNAttribute()
    {
        return date_format($this->created_at,"d-m-Y H:i:s");
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }
}
