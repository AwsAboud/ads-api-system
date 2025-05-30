<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AssetService
{
    /**
     * Uploads a file to the given storage path after validating its MIME type.
     *
     * @param \Illuminate\Http\UploadedFile $file  The uploaded file instance.
     * @param string $path  The directory path within the public disk to store the file.
     * @return string  The path where the file is stored.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function upload($file, $path = 'uploads'): string
    {
        // List of allowed MIME types
        $allowedfileExtension = [
            'image/jpg',
            'image/png',
            'image/jpeg',
            'image/svg',
            'image/webp',
            'application/pdf'
        ];
        $mimeType = $file->getMimeType();

        // Reject file if MIME type is not allowed
        if (!in_array($mimeType, $allowedfileExtension)) {
            throw ValidationException::withMessages([
                'file' => 'Invalid file type.'
            ]);
        }

        $originalFilename = $file->getClientOriginalName();

        // Sanitize the file name by replacing disallowed characters
        $sanitizedFilename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $originalFilename);

        // Extract and normalize the base file name (without extension)
        $baseFilename = strtolower(pathinfo($sanitizedFilename, PATHINFO_FILENAME));

        // Prevent path traversal attempts
        if (strpos($baseFilename, '..') !== false) {
            throw ValidationException::withMessages([
                'file' => 'Invalid file type.'
            ]);
        }

        // Rebuild a safe file name with the original extension
        $sanitizedFilename = $baseFilename . '.' . $file->getClientOriginalExtension();

        // Generate a unique name for the file to avoid name collisions
        $filename = uniqid() . '_' . $sanitizedFilename;

        // Store the file in the specified directory on the public disk
        try {
            $storagePath = Storage::disk('public')->putFileAs($path, $file, $filename);
        } catch (\Exception $e) {
            Log::error('File upload failed.', [
                'message' => $e->getMessage(),
                'file' => $originalFilename ?? 'unknown',
            ]);

            throw new \Exception('Failed to store the file.');
        }

        return $storagePath;
    }

    /**
     * Deletes a file from the given storage path.
     *
     * @param string $filePath  The relative file path to delete (e.g., 'uploads/image.png').
     * @return bool  True if file was deleted, false otherwise.
     *
     * @throws \Exception
     */
    public function delete(string $filePath): bool
    {
        try {
            if (!Storage::disk('public')->exists($filePath)) {
                return false;
            }

            return Storage::disk('public')->delete($filePath);
        } catch (\Exception $e) {
            Log::error('File deletion failed.', [
                'message' => $e->getMessage(),
                'file' => $filePath,
            ]);

            throw new \Exception('Failed to delete the file.');
        }
    }
}
