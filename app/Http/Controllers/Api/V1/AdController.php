<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ad;
use App\Services\AdService;
use App\Http\Resources\AdResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\StoreAdRequest;
use App\Http\Requests\Ad\UpdateAdRequest;

class AdController extends Controller
{
    public function __construct(protected AdService $adService){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $res = $this->adService->getAll();

        return $this->successResponse(AdResource::collection($res));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdRequest $request)
    {
        $ad = $this->adService->create($request->validated());
        
        return $this->successResponse(new AdResource($ad), code:201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ad $ad)
    {
        $ad = $this->adService->getOne($ad);

        return $this->successResponse(new AdResource( $ad));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdRequest $request, Ad $ad)
    {
        $ad = $this->adService->update($request->validated(), $ad);
        
        return $this->successResponse(new AdResource($ad));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $this->adService->delete($ad);

        return $this->successResponse(null, code:204);
    }
}
