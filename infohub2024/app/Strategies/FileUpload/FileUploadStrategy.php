<?php

namespace App\Strategies\FileUpload;

use Illuminate\Http\Request;
use App\Models\File;

interface FileUploadStrategy
{
    public function upload(Request $request, File $file, array $validated): void;
}
