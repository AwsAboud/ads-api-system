<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ad;
use App\Models\User;
use App\Services\AdService;
use App\Http\Resources\AdResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\StoreAdRequest;
use App\Http\Requests\Ad\UpdateAdRequest;
use App\Http\Requests\Ad\changeAdStatusRequest;

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

     public function indexByActive()
    {
        $res = $this->adService->getActive();

        return $this->successResponse(AdResource::collection($res));
    }

    public function listByUser(User $user)
    {
        $res = $this->adService-> getActiveAdsByUser($user);

        return $this->successResponse(AdResource::collection($res));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdRequest $request)
    {
        $data = $request->validated();
        $data ['user_id'] = auth()->id();
        $ad = $this->adService->create($data);
        
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

    /**
     * Update the specified resource status in storage.
     */
    public function changeStatus(changeAdStatusRequest $request, Ad $ad)
    {
        $status = $request->validated()['status'];
        $ad = $this->adService->changeStatus( $status, $ad);
        
        return $this->successResponse(new AdResource($ad));
    }
}
