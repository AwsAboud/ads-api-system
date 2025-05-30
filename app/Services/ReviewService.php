<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewService
{
    /**
     * Relations to eager load.
     *
     * @var array<int, string>
     */
    protected array $relations = [];

    /**
     * ReviewService constructor.
     *
     * Initialize relations or other dependencies here.
     */
    public function __construct()
    {
        //$this->relations = [];
    }

    /**
     * Get paginated list of reviews with optional eager loaded relations.
     *
     * @return LengthAwarePaginator<int, Review>
     */
    public function getAll(): LengthAwarePaginator
    {
        return Review::with($this->relations)->paginate(10);
    }

    /**
     * Get a single review with optional eager loaded relations.
     *
     * @param Review $review
     * @return Review
     */
    public function getOne(Review $review): Review
    {
        return $review->load($this->relations);
    }

    /**
     * Create a new review record.
     *
     * @param array<string, mixed> $data
     * @return Review
     */
    public function create(array $data): Review
    {
        $review = Review::create($data);

        return $review->load($this->relations);
    }

    /**
     * Update an existing review record.
     *
     * @param array<string, mixed> $data
     * @param Review $review
     * @return Review
     */
    public function update(array $data, Review $review): Review
    {
        $review->update($data);

        return $review->load($this->relations);
    }

    /**
     * Delete a review record.
     *
     * @param Review $review
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(Review $review): bool
    {
        return $review->delete();
    }
}
