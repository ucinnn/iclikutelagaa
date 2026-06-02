<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\HtmlHelper;


class FAQ extends Model
{
    protected $table = 'faqs';
    protected $fillable = [
        'category',
        'question',
        'answer',
        'created_by',
        'updated_by',
        'keterangan',
    ];

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    /**
     * Get decoded answer
     */
    public function getAnswerDecodedAttribute(): ?string
    {
        return HtmlHelper::decode($this->answer);
    }

    /**
     * Get plain text answer
     */
    public function getAnswerPlainAttribute(): ?string
    {
        return HtmlHelper::toPlainText($this->answer);
    }

    /**
     * Set answer with HTML encoding
     */
    public function setAnswerAttribute($value): void
    {
        // Jika ingin auto-encode saat save
        // $this->attributes['answer'] = HtmlHelper::encode($value);

        // Atau purify untuk keamanan
        $this->attributes['answer'] = HtmlHelper::purify($value);
    }

    // Accessor untuk mendapatkan kategori dengan format title case
    public function getCategoryNameAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->category));
    }

    // Mendapatkan icon berdasarkan kategori
    public function getCategoryIconAttribute()
    {
        $icons = [
            'general' => 'fa-info-circle',
            'account' => 'fa-user-circle',
            'content' => 'fa-newspaper',
            'advertising' => 'fa-briefcase',
            'technical' => 'fa-headset',
        ];

        return $icons[$this->category] ?? 'fa-question-circle';
    }
}