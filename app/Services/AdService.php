<?php

namespace App\Services;

use App\Models\Ad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdService
{
    protected ImageService $imageService;

    // Relations to eager load when fetching ads
    protected array $relations;
    
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Get all ads with related models.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll()
    {
        return Ad::with($this->relations)->paginate(10);
    }

    /**
     * Get a single ad instance with related models.
     *
     * @param Ad $ad The ad instance to return.
     * @return Ad The ad instance.
     */
    public function getOne(Ad $ad): Ad
    {
        return $ad->load($this->relations);
    }

    /**
     * Create a new ad and attach uploaded images if provided.
     *
     * @param array $data Ad data, including optional 'images' (array of uploaded files).
     * @return Ad The created ad.
     *
     * @throws \Throwable If something goes wrong during the process.
     */
    public function create(array $data): Ad
    {
        try {
            return DB::transaction(function () use ($data) {
                $ad = Ad::create($data);

                if (isset($data['images'])) {
                    $this->imageService->uploadImagesAndAttach($ad, $data['images']);
                }

                return $ad->load($this->relations);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to create ad: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing ad and sync uploaded images if provided.
     *
     * @param array $data Ad data, including optional 'images' (array of uploaded files).
     * @param Ad $ad The ad to update.
     * @return Ad The updated ad.
     *
     * @throws \Throwable If something goes wrong during the process.
     */
    public function update(array $data, Ad $ad): Ad
    {
        try {
            return DB::transaction(function () use ($data, $ad) {
                $ad->update($data);

                if (isset($data['images'])) {
                    $this->imageService->uploadImagesAndAttach($ad, $data['images']);
                }

                return $ad->load($this->relations);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to update ad: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an ad.
     *
     * @param Ad $ad The ad model instance to delete.
     * @return bool True if deletion was successful, false otherwise.
     */
    public function delete(Ad $ad): bool
    {
        return $ad->delete();
    }
}
