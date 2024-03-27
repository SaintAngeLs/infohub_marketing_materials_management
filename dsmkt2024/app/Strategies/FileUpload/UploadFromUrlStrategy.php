<?php

namespace App\Strategies\FileUpload;

use Illuminate\Http\Request;
use App\Models\File;

class UploadFromUrlStrategy implements FileUploadStrategy
{
    public function upload(Request $request, File $file, array $validated): void
    {
        // Assuming $validated['server_file'] contains the path
        $file->path = $validated['file_external'];
    }
}