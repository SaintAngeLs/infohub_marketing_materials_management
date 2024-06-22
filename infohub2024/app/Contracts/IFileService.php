<?php

namespace App\Contracts;

use App\Models\File;
use Illuminate\Http\Request;

interface IFileService
{
    public function getMenuItemsToSelect();
    public function getAutos();
    public function getServerFiles();
    public function validateRequest(Request $request, $isStore = true);
    public function handleFileUpload(Request $request, File &$file, array $validated);
    public function updateFileModel(File &$file, array $validated);
    public function deleteFile(File $file);
    public function downloadFile(File $file);
    public function toggleStatus(File $file);
    public function detectFileChanges(File $file, array $validated): bool;
    public function downloadMultipleFiles(Request $request);
    public function getDirectoryStructure();
}
