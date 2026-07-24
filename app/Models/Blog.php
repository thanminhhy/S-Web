<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'description',
        'content',
        'rating_count',
        'rating_avg'
    ];
    public function ratings()
    {
        return $this->hasMany(BlogRating::class, 'blog_id');
    }
}
