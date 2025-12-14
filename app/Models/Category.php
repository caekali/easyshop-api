<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'image_url'
    ];

     public function getImageUrlAttribute($value)
    {
        return $value ? Storage::disk('public')->url($value) : null;
    }

    public function products() : HasMany{
        return $this->hasMany(Product::class);
    }
}
