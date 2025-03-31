<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $media = Media::findOrFail($id);

        // Check if the user has permission to access this media
        // You can add additional checks here based on user role or ownership

        // Get the full path to the file
        $path = $media->getPath();

        // Return the file with proper headers
        return response()->file($path, [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
            'Cache-Control' => 'public, max-age=86400'
        ]);
    }

    /**
     * Download the specified media file
     *
     * @param int $id
     * @return StreamedResponse
     */
    public function download($id)
    {
        $media = Media::findOrFail($id);

        // Check if the user has permission to access this media
        // You can add additional checks here based on user role or ownership

        return response()->download(
            $media->getPath(),
            $media->file_name,
            [
                'Content-Type' => $media->mime_type,
                'Content-Disposition' => 'attachment; filename="' . $media->file_name . '"'
            ]
        );
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
        $media = Media::findOrFail($id);

        if (!$media->hasGeneratedConversion($conversion)) {
            $conversion = '';  // Use original if conversion doesn't exist
        }

        $path = $conversion ? $media->getPath($conversion) : $media->getPath();

        return response()->file($path, [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
            'Cache-Control' => 'public, max-age=86400'
        ]);
    }
}
