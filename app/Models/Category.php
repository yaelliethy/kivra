<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasUuids, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'image_url',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
