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
        Log::info('Inside the strategy for serverFiles');
        $fileHash = $validated['server_file'];
        Log::info("File hash received: {$fileHash}");

        $originalFilePath = $this->resolveFilePathFromHash($fileHash);

        if (!$originalFilePath) {
            Log::error("File path could not be resolved from hash: {$fileHash}");
            throw new \Exception("File path could not be resolved from hash.");
        }

        $extension = pathinfo($originalFilePath, PATHINFO_EXTENSION);
        $filename = $validated['name'] . '.' . pathinfo($originalFilePath, PATHINFO_EXTENSION);
        $targetDirectory = 'menu_files/' . $validated['menu_id'];

        $moveResult = Storage::disk('public')->move($originalFilePath, $targetDirectory . '/' . $filename);
        if ($moveResult) {
            $file->path = $targetDirectory . '/' . $filename;
            $file->extension = $extension;
            $finalPath = Storage::disk('public')->path($file->path);
            $file->weight = filesize($finalPath);
            $file->file_source = 'file_server';
            Log::info("File successfully moved: {$file->path}");
        } else {
            Log::error("Failed to move file: {$originalFilePath} to {$targetDirectory}/{$filename}");
            throw new \Exception("Failed to move the file.");
        }
    }

    protected function resolveFilePathFromHash($hash)
    {
        $filePathMap = $this->secureHashToPathMap();
        return $filePathMap[$hash] ?? null;
    }

    protected function secureHashToPathMap()
    {
        $dynamicMap = $this->fetchDynamicPathMap();
        Log::info('Dynamic file path map created', $dynamicMap);
        return $dynamicMap;
    }

    private function fetchDynamicPathMap() {
        $files = Storage::disk('public')->files('ftp_upload');
        $map = [];
        foreach ($files as $filePath) {
            $relativePath = 'ftp_upload/' . basename($filePath); // Ensure the path structure matches getDirectoryStructure
            $hash = md5($relativePath);
            $map[$hash] = $relativePath;
        }
        return $map;
    }


}
