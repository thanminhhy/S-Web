<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    //Tự đính kèm thông tin user vào comment để lấy tên và ava
    protected $with = ['user'];

    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_id',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
