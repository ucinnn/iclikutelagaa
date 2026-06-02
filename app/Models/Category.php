<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Facades\Filament;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $fillable = [
        'name',
        'slug',
        'created_by',
        'updated_by',
        'keterangan',
    ];

    public function news()
    {
        return $this->belongsToMany(News::class, 'news_category', 'category_id', 'news_id');
    }
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
            if (Filament::auth()->user()) {
                $user = Filament::auth()->user();
                $model->created_by = $user->name . ' (' . $user->NIK . ')';
                $model->updated_by = $user->name . ' (' . $user->NIK . ')';
            }
        });

        static::updating(function ($model) {
            if (Filament::auth()->user()) {
                $user = Filament::auth()->user();
                $changed = [];


                foreach ($model->getDirty() as $key => $value) {
                    $original = $model->getOriginal($key);

                    $changed[] = "$key: '$original' → '$value'";
                }

                // Jika ada perubahan penting, catat ke updated_by dan keterangan
                if (count($changed) > 0) {
                    $model->updated_by = $user->name . ' (' . $user->NIK . ')';
                    $model->keterangan = 'Change: ' . Str::limit(implode(', ', $changed), 255);
                } else {
                    // Jangan ubah nilai yang ada di DB
                    $model->updated_by = $model->getOriginal('updated_by');
                    $model->keterangan = $model->getOriginal('keterangan');
                }
            }
        });
    }
}
