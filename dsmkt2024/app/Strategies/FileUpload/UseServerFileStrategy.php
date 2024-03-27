<?php

namespace App\Strategies\FileUpload;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UseServerFileStrategy implements FileUploadStrategy
{
    public function upload(Request $request, File $file, array $validated): void
    {
        if (!empty($validated['server_file'])) {
            $originalFilePath = 'ftp_upload/' . $validated['server_file'];
            $filename = $validated['name'] . '.' . pathinfo($validated['server_file'], PATHINFO_EXTENSION);
            $targetDirectory = 'menu_files/' . $validated['menu_id'];

            if (Storage::disk('public')->exists($originalFilePath)) {
                Log::info("Attempting to move file", ['from' => $originalFilePath, 'to' => $targetDirectory . '/' . $filename]);

                $newFilePath = Storage::disk('public')->move($originalFilePath, $targetDirectory . '/' . $filename);

                Log::info("File moved successfully", ['newPath' => $newFilePath]);

                $file->path = $targetDirectory . '/' . $filename;
            } else {
                throw new \Exception("The specified file does not exist in the server's 'ftp_upload' directory.");
            }
        }
    }
}
