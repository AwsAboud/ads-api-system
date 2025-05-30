<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\StoreAdRequest;
use App\Http\Requests\Ad\UpdateAdRequest;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Services\AdService;
use Illuminate\Http\Request;

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
        $ad = $this->adService->getOne();

        return $this->successResponse(AdResource::collection( $ad));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdRequest Request  $request, Ad $ad)
    {
        $ad = $this->adService->update($request->validated());
        
        return $this->successResponse(new AdResource($ad));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $ad->delete();
        return $this->successResponse(null, code:204);
    }
}
