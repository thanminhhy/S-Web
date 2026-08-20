<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'name',
        'price',
        'status',
        'sale',
        'company',
        'images',
        'detail',
        'brand_id',
        'category_id',
        'user_id',
    ];

    //Tự động cast cột 'images' sang kiểu array
    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
