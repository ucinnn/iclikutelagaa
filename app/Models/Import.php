<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'status',
        'file_path',
        'errors',
        'meta',
        'user_id',
    ];

    protected $casts = [
        'errors' => 'array',
        'meta' => 'array',
    ];
}
