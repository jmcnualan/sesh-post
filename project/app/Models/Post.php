<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public const RESOURCE_NAME = 'Post';

    protected $fillable = [
        'title',
        'content',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
