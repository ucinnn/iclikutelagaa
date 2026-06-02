<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PopUpNews extends Model
{
    protected $table = 'pop_up_news';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_active',
        'start_at',
        'end_at',
        'image',
        'author',
        'updated_by',
        'keterangan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'content' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
