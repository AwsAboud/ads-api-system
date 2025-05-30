<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ad extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'status',
    ];

     /**
     * Scope to filter ads based on user role and status visibility.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            // Admin sees all ads
            return $query;
        }  
        // Regular users see:
        // - ads with status 'active'
        // - their own ads even if 'pending' or 'rejected'
        return $query->where(function ($q) use ($user) {
            $q->where('status', 'active')
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('user_id', $user->id)
                     ->whereIn('status', ['pending', 'rejected']);
              });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

     /**
     * Accessor & Mutator for price.
     * - Stores price in cents.
     * - Returns price in dollars.
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value / 100,
            set: fn (float|int $value) => $value * 100 
        );
    }
}
