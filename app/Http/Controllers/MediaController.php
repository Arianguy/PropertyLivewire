<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    /**
     * Display the specified media file securely
     *
     * @param int $id
     * @return StreamedResponse
     */
    public function show($id)
    {
        try {
            $media = Media::findOrFail($id);

            // Get the path to the file
            $path = $media->getPath();

            // Verify file exists
            if (!file_exists($path)) {
                Log::error("Media file not found at path: {$path}");
                abort(404, 'Media file not found');
            }

            // Set appropriate headers based on file type
            $headers = $this->getSecureHeaders($media);

            // Return the file with proper headers
            return response()->file($path, $headers);
        } catch (\Exception $e) {
            Log::error("Error accessing media: " . $e->getMessage());
            abort(500, 'Error accessing media file');
        }
    }

    /**
     * Download the specified media file
     *
     * @param int $id
     * @return StreamedResponse
     */
    public function download($id)
    {
        try {
            $media = Media::findOrFail($id);

            // Verify file exists
            $path = $media->getPath();
            if (!file_exists($path)) {
                Log::error("Media file not found at path: {$path}");
                abort(404, 'Media file not found');
            }

            return response()->download(
                $path,
                $media->file_name,
                $this->getSecureHeaders($media, true)
            );
        } catch (\Exception $e) {
            Log::error("Error downloading media: " . $e->getMessage());
            abort(500, 'Error downloading media file');
        }
    }

    /**
     * Display a thumbnail for the media
     *
     * @param int $id
     * @param string $conversion
     * @return StreamedResponse
     */
    public function thumbnail($id, $conversion = 'thumb')
    {
        try {
            $media = Media::findOrFail($id);

            if (!$media->hasGeneratedConversion($conversion)) {
                $conversion = '';  // Use original if conversion doesn't exist
            }

            $path = $conversion ? $media->getPath($conversion) : $media->getPath();

            // Verify file exists
            if (!file_exists($path)) {
                Log::error("Thumbnail not found at path: {$path}");
                abort(404, 'Thumbnail not found');
            }

            return response()->file($path, $this->getSecureHeaders($media));
        } catch (\Exception $e) {
            Log::error("Error accessing thumbnail: " . $e->getMessage());
            abort(500, 'Error accessing thumbnail');
        }
    }

    /**
     * Get secure headers for media response
     *
     * @param Media $media
     * @param bool $isDownload
     * @return array
     */
    protected function getSecureHeaders(Media $media, bool $isDownload = false): array
    {
        $disposition = $isDownload ? 'attachment' : 'inline';

        $headers = [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => $disposition . '; filename="' . $media->file_name . '"',
            'Cache-Control' => 'private, no-transform, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block'
        ];

        // Add specific headers for images
        if (str_starts_with($media->mime_type, 'image/')) {
            $headers['Content-Security-Policy'] = "default-src 'self'";
        }

        return $headers;
    }
}
