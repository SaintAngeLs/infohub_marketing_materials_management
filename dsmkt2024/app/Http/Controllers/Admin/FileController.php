<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\IFileService;
use App\Contracts\IStatistics;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    protected $fileService;
    protected $userService;
    protected $statisticsService;

    public function __construct(IFileService $fileService, UserService $userService, IStatistics $statisticsService)
    {
        $this->fileService = $fileService;
        $this->userService = $userService;
        $this->statisticsService = $statisticsService;
    }

    public function create(Request $request)
    {
        $menuItemsToSelect = $this->fileService->getMenuItemsToSelect();
        $autos = $this->fileService->getAutos();
        $serverFiles = $this->fileService->getServerFiles();
        $selectedMenuItemId = $request->query('menu_item_id');

        return view('admin.files.create', compact('menuItemsToSelect', 'autos', 'serverFiles', 'selectedMenuItemId'));
    }

    public function edit($fileId)
    {
        $file = File::findOrFail($fileId);
        $menuItemsToSelect = $this->fileService->getMenuItemsToSelect();
        $autos = $this->fileService->getAutos();
        $serverFiles = $this->fileService->getServerFiles();
        $selectedMenuItemId = $file->menu_id;

        return view('admin.files.edit', compact('file', 'menuItemsToSelect', 'autos', 'serverFiles', 'selectedMenuItemId'));
    }

    public function store(Request $request)
    {
        Log::info('Store request ', $request->all());
        $validated = $this->fileService->validateRequest($request, true);

        try {
            $file = new File();
            $this->fileService->handleFileUpload($request, $file, $validated);
            $this->fileService->updateFileModel($file, $validated);

            $fileChanged = $this->fileService->detectFileChanges($file, $validated);

            if ($fileChanged) {
                Log::info("fileChanged are set to true ... sending the email");
                $queryString = $request->getQueryString();
                $this->statisticsService->logUserActivity(auth()->id(), [
                    'uri' => $request->path(),
                    'post_string' => json_encode($request->all()),
                    'query_string' => $queryString,
                ]);
                $this->userService->notifyUserAboutFileChange($file->menu_id, "File changes initiated.");
            }
            return back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, File $file)
    {
        $validated = $this->fileService->validateRequest($request, false);

        try {
            $this->fileService->handleFileUpload($request, $file, $validated);
            $this->fileService->updateFileModel($file, $validated);

            $fileChanged = $this->fileService->detectFileChanges($file, $validated);

            if ($fileChanged) {
                Log::info("fileChanged are set to true ... sending the email");
                $queryString = $request->getQueryString();
                $this->statisticsService->logUserActivity(auth()->id(), [
                    'uri' => $request->path(),
                    'post_string' => json_encode($request->all()),
                    'query_string' => $queryString,
                ]);
                $this->userService->notifyUserAboutFileChange($file->menu_id, "A file in your subscribed menu item has been updated.");
            }
            Log::info("Redirecting to the menu-files");
            return redirect()->route('menu.files')->with('success', 'File updated successfully.');
        } catch (\Exception $e) {
            Log::error('File update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'File update failed: ' . $e->getMessage());
        }
    }

    public function deleteFile($id)
    {
        $file = File::findOrFail($id);
        $this->fileService->deleteFile($file);
        return back()->with('success', 'File has been deleted.');
    }

    public function download($fileId)
    {
        $file = File::findOrFail($fileId);
        return $this->fileService->downloadFile($file);
    }

    public function toggleStatus($id)
    {
        Log::info('Attempting to toggle status for file: ' . $id);
        $file = File::findOrFail($id);
        $newStatus = $this->fileService->toggleStatus($file);
        return response()->json(['success' => true, 'newStatus' => $newStatus]);
    }

    public function downloadMultiple(Request $request)
    {
        return $this->fileService->downloadMultipleFiles($request);
    }
}
