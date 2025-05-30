<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $res = $this->reviewService->getAll();

        return $this->successResponse(ReviewResource::collection($res));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $review = $this->reviewService->create($request->validated());
        
        return $this->successResponse(new ReviewResource($review), code:201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $review = $this->reviewService->getOne($review);

        return $this->successResponse(new ReviewResource( $review));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review = $this->reviewService->update($request->validated(), $review);
        
        return $this->successResponse(new ReviewResource($review));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
       $this->reviewService->delete($review);

        return $this->successResponse(null, code:204);
    }
}
