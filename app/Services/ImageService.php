<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ImageService
{

    public function __construct(protected AssetService $assetService) {}

    /**
     * Upload one or more image files and immediately attach them to a model.
     *
     * @param Model $model  The model to associate the images with (e.g., Event, Location).
     * @param array|\Illuminate\Http\UploadedFile $uploadedImages One or more uploaded image files.
     * @param string $path  Optional subdirectory for storing images.
     * @return Image[]      Array of created Image model instances.
     */
    public function uploadImagesAndAttach(Model $model, array|UploadedFile $uploadedImages, string $path = 'images'): array
    {
        $uploadedImages = is_array($uploadedImages) ? $uploadedImages : [$uploadedImages];

        $createdImages = [];

        foreach ($uploadedImages as $uploadedImage) {
            $storagePath = $this->assetService->upload($uploadedImage, $path);

            $createdImages[] = $model->images()->create([
                'path' => $storagePath,
            ]);
        }

        return $createdImages;
    }
}
