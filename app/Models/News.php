<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tags;
use App\Models\Category;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class News extends Model
{
    use HasFactory;
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'tags',
        'author',
        'updated_by',
        'published_at',
        'content',
        'thumbnail',
        'status',
        'published_at',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'keterangan',
        'views',
        'featured',
        'featuredvideo',
        'news_id',
        'news_category',
        'news_tags',
        'news_user',
        'tags_id',
    ];

    protected $casts = [
        'content' => 'array',
        'tags' => 'array',
        'image_url' => 'array',
        'video_url' => 'array',
        'published_at' => 'datetime',
    ];
    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Helper methods
    public function isPublished(): bool
    {
        return $this->status === 'published'
            && ($this->published_at === null || $this->published_at->isPast());
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
    /** =====================
     *  RELATIONS
     *  ===================== */

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'news_users');
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'news_tags', 'news_id', 'tags_id');
    }


    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'news_category', 'news_id', 'category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** =====================
     *  MODEL EVENTS
     *  ===================== */


    public function getImageUrlAttribute()
    {
        // 1️⃣ Jika ada thumbnail manual
        if (!empty($this->thumbnail)) {
            // Jika thumbnail berupa URL eksternal (misal Google Drive / OneDrive)
            if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
                return convertExternalImageUrl($this->thumbnail);
            }

            // Jika file lokal tersimpan di storage
            if (Storage::exists($this->thumbnail)) {
                return asset('storage/' . $this->thumbnail);
            }
        }

        // 2️⃣ Ambil dari konten (Editor.js / JSON / HTML)
        if (!empty($this->content)) {
            $content = $this->content;

            // Jika array (misal dari JSON editor)
            if (is_array($content)) {
                foreach ($content as $block) {
                    if (isset($block['type']) && $block['type'] === 'image') {
                        $src = $block['data']['url_link'] ?? $block['data']['url'] ?? $block['attrs']['src'] ?? null;
                        if ($src) {
                            return Str::startsWith($src, ['http://', 'https://'])
                                ? convertExternalImageUrl($src)
                                : asset('storage/' . ltrim($src, '/'));
                        }
                    }

                    // Cek nested content
                    if (isset($block['content']) && is_array($block['content'])) {
                        foreach ($block['content'] as $child) {
                            if (($child['type'] ?? null) === 'image') {
                                $src = $child['attrs']['src'] ?? null;
                                if ($src) {
                                    return Str::startsWith($src, ['http://', 'https://'])
                                        ? convertExternalImageUrl($src)
                                        : asset('storage/' . ltrim($src, '/'));
                                }
                            }
                        }
                    }
                }

                // fallback: ubah ke string untuk deteksi <img>
                $content = json_encode($content);
            }

            // Jika string HTML biasa
            if (is_string($content) && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches)) {
                $src = $matches[1];
                return Str::startsWith($src, ['http://', 'https://'])
                    ? convertExternalImageUrl($src)
                    : asset('storage/' . ltrim($src, '/'));
            }
        }

        // 3️⃣ Fallback: logo dari ENV atau default
        $envLogo = env('APP_LOGO');
        if (!empty($envLogo)) {
            return Str::startsWith($envLogo, ['http://', 'https://'])
                ? $envLogo
                : asset($envLogo);
        }

        return asset('images/logo.png');
    }


    public function getContentAttribute($value)
    {
        // Jika null, kembalikan array kosong
        if (is_null($value)) return [];

        // Jika sudah array (hasil casting), kembalikan langsung
        if (is_array($value)) return $value;

        // Jika JSON valid
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Jika string HTML biasa
        return [$value];
    }
    protected static function booted(): void
    {
        static::saving(function ($model) {
            // === Auto-set published_at ===
            if ($model->status === 'published' && empty($model->published_at)) {
                $model->published_at = now();
            }

            if (in_array($model->status, ['draft', 'scheduled']) && !$model->published_at) {
                $model->published_at = null;
            }

            // === Periksa apakah ada block video yang di-mark sebagai featured ===
            if (is_array($model->content)) {
                $isFeatured = false;

                foreach ($model->content as $block) {
                    if (
                        isset($block['type']) &&
                        $block['type'] === 'video' &&
                        !empty($block['data']['featuredvideo'])
                    ) {
                        $isFeatured = true;
                        break;
                    }
                }

                $model->featuredvideo = $isFeatured;
            }
        });

        // === Generate slug otomatis saat membuat data baru ===
        static::creating(function (News $news) {
            $news->slug = Str::slug($news->title);

            if ($user = Filament::auth()->user()) {
                $news->author = $user->name . ' (' . $user->NIK . ')';
                $news->updated_by = $user->name . ' (' . $user->NIK . ')';
            }
        });

        static::retrieved(function ($news) {
            if ($news->status === 'scheduled' && $news->published_at && now()->gte($news->published_at)) {
                $news->update(['status' => 'published']);
            }
        });

        // === Update metadata saat data diubah ===
        static::updating(function (News $news) {
            if ($user = Filament::auth()->user()) {
                $changed = [];

                foreach ($news->getDirty() as $key => $value) {
                    $original = $news->getOriginal($key);

                    if (is_array($original)) {
                        $original = json_encode($original);
                    }
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }

                    $changed[] = "$key: '$original' → '$value'";
                }

                if (!empty($changed)) {
                    $news->updated_by = $user->name . ' (' . ($user->NIK ?? '-') . ')';
                    $news->keterangan = 'Change: ' . Str::limit(implode(', ', $changed), 255);
                } else {
                    $news->updated_by = $news->getOriginal('updated_by');
                    $news->keterangan = $news->getOriginal('keterangan');
                }
            }
        });
    }
    public function setUrlLinkAttribute($value)
    {
        if (preg_match('/drive\.google\.com.*\/d\/([^\/]+)/', $value, $matches)) {
            $this->attributes['url_link'] = "https://drive.google.com/uc?export=view&id={$matches[1]}";
        } elseif (preg_match('/onedrive\.live\.com/', $value)) {
            $this->attributes['url_link'] = str_replace(['redir?', 'view?'], 'download?', $value);
        } else {
            $this->attributes['url_link'] = $value;
        }
    }
}
