<?php

namespace App\Traits;

trait UsesFiles
{
    public function formatFiles($files)
    {
        return $files->map(function ($file) {
            return [
                'uuid' => $file->uuid,
                'name' => $file->name,
                'size' => $file->size,
                'type' => explode('/', $file->mime_type)[0],
                'preview' => $file->getFullUrl() ?? null
            ];
        })->toArray();
    }
}