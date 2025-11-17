<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'template',
        'status',
        'order',
        'is_homepage',
        'show_in_menu',
        'featured_image',
        'custom_fields',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
        'show_in_menu' => 'boolean',
        'custom_fields' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            if ($page->status === 'published' && !$page->published_at) {
                $page->published_at = now();
            }
            if ($page->is_homepage) {
                // Unset other homepage
                static::where('is_homepage', true)->update(['is_homepage' => false]);
            }
        });

        static::updating(function ($page) {
            if ($page->is_homepage) {
                // Unset other homepage
                static::where('is_homepage', true)->where('id', '!=', $page->id)->update(['is_homepage' => false]);
            }
            if ($page->status === 'published' && !$page->published_at) {
                $page->published_at = now();
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)
            ->where('status', 'published')
            ->orderBy('order');
    }

    public function getUrlAttribute()
    {
        if ($this->is_homepage) {
            return route('home');
        }
        return route('page.show', $this->slug);
    }
}
