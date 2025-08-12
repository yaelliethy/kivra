<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Product extends Model
{
    use HasUuids;
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'category_id',
        'stock',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
