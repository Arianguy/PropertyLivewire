<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProtectMediaAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get the media ID from the route parameters
        $mediaId = $request->route('id');

        try {
            // Find the media
            $media = Media::findOrFail($mediaId);

            // Get the model that owns the media
            $model = $media->model;

            // Check if user has permission to access this type of media
            $user = Auth::user();
            $modelType = strtolower(class_basename($model));

            // Super Admin can access all media
            if ($user->hasRole('Super Admin')) {
                return $next($request);
            }

            // Check specific permissions based on model type
            $permissionMap = [
                'receipt' => 'view receipts',
                'contract' => 'view contracts',
                'property' => 'view properties',
                // Add more model types and their corresponding permissions here
            ];

            // If model type has a permission mapping and user has permission
            if (isset($permissionMap[$modelType]) && $user->can($permissionMap[$modelType])) {
                return $next($request);
            }

            // If no permission mapping exists or user doesn't have permission
            abort(403, 'Unauthorized access to media');
        } catch (\Exception $e) {
            abort(404, 'Media not found');
        }
    }
}
