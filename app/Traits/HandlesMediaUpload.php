<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;

trait HandlesMediaUpload
{
    protected function handleMediaUpload(Request $request, HasMedia $model, string $collection = 'images'): void
    {
        if ($request->hasFile('image')) {
            $model->clearMediaCollection($collection);
            $model->addMediaFromRequest('image')->toMediaCollection($collection, 'media');
        }
    }
}
