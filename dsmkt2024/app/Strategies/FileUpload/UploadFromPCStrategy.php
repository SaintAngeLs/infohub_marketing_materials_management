<?php

namespace App\Strategies\FileUpload;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class UploadFromPCStrategy implements FileUploadStrategy
{
    public function upload(Request $request, File $file, array $validated): void
    {
        $uploadedFile = $request->file('file');
        $filename = $validated['name'] . '.' . $uploadedFile->getClientOriginalExtension();
        $directory = 'menu_files/' . $validated['menu_id'];
        $filePath = $uploadedFile->storeAs($directory, $filename, 'public');
        $file->path = $filePath;
    }
}
