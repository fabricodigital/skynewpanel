<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Media;

class MediaController extends Controller
{
    /**
     * @param \App\Models\Admin\Media $media
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Media $media)
    {
        $model = $media->model;

        $this->authorize('delete_media', $model);

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => $media->file_name . ' ' . __('deleted successfully!')
        ], 201);
    }

    public function download(Media $media)
    {
        $model = $media->model;

        $this->authorize('download', $model);

        return response()->download($media->getPath());
    }
}
