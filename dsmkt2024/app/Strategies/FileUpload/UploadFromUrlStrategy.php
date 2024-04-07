<?php

namespace App\Strategies\FileUpload;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Log;
class UploadFromUrlStrategy implements FileUploadStrategy
{
    /**
     * Handle file upload from an external URL.
     *
     * Instead of uploading and storing the file on the server,
     * this strategy saves the URL directly in the File model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @param  array  $validated  Validated request data
     */
    public function upload(Request $request, File $file, array $validated): void
    {
        Log::info('Inside the strategy for external URL files');

        $file->path = $validated['file_url'];
        $file->file_source = 'file_external';

        $urlPath = parse_url($file->path, PHP_URL_PATH);
        $filename = basename($urlPath);

        if (empty($validated['name'])) {
            $file->name = $filename;
        } else {
            $file->name = $validated['name'];
        }

        $file->extension = pathinfo($filename, PATHINFO_EXTENSION);
        $file->hosted = '1';
        $file->weight = null;

        Log::info('File information set for external URL:', ['file' => $file]);
    }
}
