<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'category_id' => $this->category_id,
            'title'       => $this->title,
            'description' => $this->description,
            'price'       => $this->price,
            'status'      => $this->status,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'cover_image'  => new ImageResource($this->whenLoaded('latestImage')),
            'reviews_count' => $this->reviews_count ?? null,
         
        ];
    }
}
