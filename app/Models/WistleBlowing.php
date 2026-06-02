<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WistleBlowing extends Model
{
    protected $table = 'wistle_blowings';

    protected $fillable = [
        'user_id',
        'subject',
        'category',
        'division',
        'description',
        'proof',
        'status',
        'links',
    ];

    protected $casts = [
        'proof' => 'array',
        'links' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}