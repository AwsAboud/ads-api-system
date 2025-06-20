<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class AdService
{
    // Relations to eager load when fetching Ads.
    protected array $relations = [];

    public function __construct()
    {
        $this->relations = ['images', 'latestImage'];
    }

    /**
     * Get all Ads with related models.
     *
     * @return LengthAwarePaginator<int, Ad>
     */
    public function getAll(): LengthAwarePaginator
    {
        return Ad::visibleTo(auth()->user())
        ->with($this->relations)
        ->withCount('reviews')
        ->paginate(10);
    }

    public function getActive()
    {
        $cacheKey = 'ads_active_ordered_by_views';
        $ttl = now()->addHours(2);

        return Cache::remember($cacheKey, $ttl, function () {
            return Ad::active()
                ->orderedByViews()
                ->with($this->relations)
                ->withCount('reviews')
                ->get();
        });
    }

     public function getActiveAdsByUser(User $user)
    {
        $ads = Ad::whereHas('user', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->active()
        ->orderedByViews()
        ->with($this->relations)
        ->withCount('reviews')
        ->paginate(10);

        return $ads;
    }

    /**
     * Get a single Ad instance with related models.
     *
     * @param Ad $ad The Ad instance to return.
     * @return Ad The Ad instance.
     */
    public function getOne(Ad $ad): Ad
    {
        // Ensure the ad is visible to the current user
        $ad = Ad::visibleTo(auth()->user())
            ->where('id', $ad->id)
            ->with($this->relations)
            ->firstOrFail();

         // Safely increment views using locking
        DB::transaction(function () use ($ad) {
            $ad->lockForUpdate();
            $ad->increment('views');
        });

        return $ad->load($this->relations);
    }

    /**
     * Create a new Ad.
     *
     * @param array<string, mixed> $data Ad data.
     * @return Ad The created Ad.
     */
    public function create(array $data): Ad
    {
        $ad = Ad::create($data);

        return $ad->load($this->relations);
    }

    /**
     * Update an existing Ad.
     *
     * @param array<string, mixed> $data Ad data.
     * @param Ad $ad The Ad to update.
     * @return Ad The updated Ad.
     */
    public function update(array $data, Ad $ad): Ad
    {
        $ad->update($data);

        return $ad->load($this->relations);
    }

    /**
     * Change the status of an Ad.
     * @param string $status The new status (e.g., 'pending', 'active', 'rejected').
     * @param Ad $ad The Ad instance to update.
     * @return Ad The updated Ad instance.
     */
    public function changeStatus( string $status, Ad $ad): Ad
    {
        $ad->update(['status' => $status]);

        return $ad->load($this->relations);
    }


    /**
     * Delete a Ad.
     *
     * @param Ad $ad The Ad model instance to delete.
     * @return bool True if deletion was successful, false otherwise.
     */
    public function delete(Ad $ad): bool
    {
        return $ad->delete();
    }
    
}
