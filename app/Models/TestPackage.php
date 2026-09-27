<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class TestPackage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pkg) {
            if (empty($pkg->slug)) {
                $pkg->slug = Str::slug($pkg->name);
            }
        });
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'test_package_items');
    }
}
