<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\IStatistics;
use App\Http\Controllers\Controller;
use App\Models\Auto;
use App\Models\File;
use App\Models\MenuItems\MenuItem;

use App\Models\UserNotification;
use App\Services\UserService;
use App\Strategies\FileUpload\UploadFromPCStrategy;
use App\Strategies\FileUpload\UploadFromUrlStrategy;
use App\Strategies\FileUpload\UseServerFileStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    protected $userService;
    protected $statisticsService;
    public function __construct(UserService $userService,  IStatistics $statisticsService)
    {
        $this->userService = $userService;
        $this->statisticsService = $statisticsService;
    }
    public function create(Request $request)
    {
        $menuItemsToSelect = MenuItem::all();
        $autos = Auto::all();
        $serverFiles = $this->getServerFiles();
        $selectedMenuItemId = $request->query('menu_item_id');

        return view('admin.files.create', compact('menuItemsToSelect', 'autos', 'serverFiles', 'selectedMenuItemId'));
    }

    public function edit($fileId)
    {
        $file = File::findOrFail($fileId);
        $menuItemsToSelect = MenuItem::all();
        $autos = Auto::all();
        $serverFiles = $this->getServerFiles();
        $selectedMenuItemId = $file->menu_id;

        return view('admin.files.edit', compact('file', 'menuItemsToSelect', 'autos', 'serverFiles', 'selectedMenuItemId'));
    }

    public function store(Request $request)
    {
        Log::info('Store request ',  $request->all());
        $validated = $this->validateRequest($request, true);

        try {
            $file = new File();
            $this->handleFileUpload($request, $file, $validated);
            $this->updateFileModel($file, $validated);

            $fileChanged = $this->detectFileChanges($file, $validated);

            if ($fileChanged) {
                Log::info("fileChanged are set to true ... seding the email");
                $queryString = $request->getQueryString();
                $this->statisticsService->logUserActivity(auth()->id(), [
                    'uri' => $request->path(),
                    'post_string' => json_encode($request->all()),
                    'query_string' => $queryString,
                ]);
                $this->userService->notifyUserAboutFileChange($file->menu_id, "A file in your subscribed menu item has been updated.");
            }
            return back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, File $file)
    {
        $validated = $this->validateRequest($request, false);
        $fileChanged = false;

        try {
            $this->handleFileUpload($request, $file, $validated);
            $this->updateFileModel($file, $validated);
            $fileChanged = $this->detectFileChanges($file, $validated);
            if ($fileChanged) {
                Log::info("fileChanged are set to true ... seding the email");
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

    private function validateRequest(Request $request, $isStore = true)
    {
        $rules = [
            'menu_id' => 'required|exists:menu_items,id',
            'name' => 'required|string|max:255',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'key_words' => 'nullable|string',
            'auto_id' => 'nullable|exists:autos,id',
            'file_source' => 'required|in:file_pc,file_external,file_server',
        ];

        // if ($isStore) {
        //     $rules['file'] = 'required|file|max:716800'; // 700 MB
        // } else {
        //     $rules['file'] = 'sometimes|file|max:716800';
        // }

        switch ($request->input('file_source')) {
            case 'file_pc':
                $rules['file'] = 'required|file|max:716800';
                break;
            case 'file_external':
                $rules['file_url'] = 'required|url';
                break;
            case 'file_server':
                $rules['server_file'] = 'required|string';
                break;
        }


        return $request->validate($rules);
    }

    private function handleFileUpload(Request $request, File &$file, array $validated)
    {
        if (!$request->hasFile('file') && $file->exists) {
            $this->updateFileAttributes($file, $validated, $request);
            $file->save();
            return;
        }

        Log::info('The request in the fileUpload', $request->all());
        $fileSource = $validated['file_source'];
        // Strategy initialization
        $strategy = null;

        Log::info('Validated data:', $validated);
        Log::info('File source:', ['source' => $fileSource]);


        switch ($fileSource) {
            case 'file_pc':
                if ($request->hasFile('file')) {
                    $strategy = new UploadFromPCStrategy();
                }
                break;
            case 'file_external':
                if (!empty($validated['file_url'])) {
                    $strategy = new UploadFromUrlStrategy();
                }
                break;
            case 'file_server':
                if (!empty($validated['server_file'])) {
                    $strategy = new UseServerFileStrategy();
                    Log::info("The stategor for the server file");
                }
                break;
        }
        // Log::debug("Selected strategy: " . get_class($strategy));
        if ($strategy !== null) {
            $strategy->upload($request, $file, $validated);
            if ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
                $validated['extension'] = $uploadedFile->getClientOriginalExtension();
                $validated['weight'] = $uploadedFile->getSize();
            }
            $this->updateFileAttributes($file, $validated, $request);
        } else {
            if ($file->exists && !$request->hasFile('file')) {
                Log::info("The request does not cointat in the file");
            } else {
                throw new \Exception("No valid file upload source provided or required data missing.");
            }
        }
    }

    private function updateFileAttributes(File &$file, array $validated, $request = null)
    {
        $file->menu_id = $validated['menu_id'];
        $file->name = $validated['name'];
        $file->auto_id = $validated['auto_id'] ?? null;
        $file->start = $validated['start'] ?? null;
        $file->end = $validated['end'] ?? null;
        $file->key_words = $validated['key_words'] ?? null;
        $file->status = $validated['status'] ?? 1;

        if (isset($validated['extension'])) {
            $file->extension = $validated['extension'];
        }

        if (isset($validated['weight'])) {
            $file->weight = $validated['weight'];
        }

        // Update the extension if a file is uploaded
        if ($request && $request->hasFile('file')) {
            $file->extension = $request->file('file')->getClientOriginalExtension();
        }

        $userId = auth()->id();
        $file->add_by = $file->exists ? $file->add_by : $userId;
        $file->update_by = $userId;
    }



    public function deleteFile($id)
    {
        $file = File::findOrFail($id);

        if ($file->path && Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        $file->delete();

        return back()->with('success', 'Plik został usunięty.');
    }

    private function updateFileModel(File &$file, $validated)
    {
        Log::info('UpdatefileModel function', $file->toArray());
        // foreach ($validated as $key => $value) {
        //     if ($key !== 'file') {
        //         Log::info('unpatedeFileModel fail: the file is not present');
        //         $file->$key = $value ?? null;
        //     }
        // }

        $this->updateFileAttributes($file, $validated);
        $file->save();
        Log::info('File model updated:', $file->toArray());
    }

    private function getServerFiles()
    {
        $files = Storage::disk('public')->files('menu_files');
        $serverFiles = array_map(function ($file) {
            return basename($file);
        }, $files);

        return $serverFiles;
    }

    public function download($fileId)
    {
        $file = File::findOrFail($fileId);

        if ($file->file_source == 'file_external') {
            // Redirect to the external URL
            return redirect()->away($file->path);
        }

        $filePath = storage_path('app/public/' . $file->path);
        Log::debug('File path', ['path' => $filePath]);
        Log::info('File path');
        if (!file_exists($filePath)) {
            abort(404);
        }

        $this->statisticsService->logDownload(auth()->id(), $fileId);

        return response()->download($filePath, basename($filePath), [
            'Content-Type' => mime_content_type($filePath)
        ]);
    }

    public function getDirectoryStructure() {
        $specificFolderPath = storage_path('app/public/ftp_upload');
        $secureStructure = [];

        if (\File::isDirectory($specificFolderPath)) {
            $files = \File::files($specificFolderPath);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $relativeFilePath = 'ftp_upload/' . $fileName;
                $fileId = md5($relativeFilePath);

                $secureStructure['ftp_upload'][] = [
                    'id' => $fileId,
                    'name' => $fileName,
                ];
            }
        }

        return response()->json($secureStructure);
    }

    public function toggleStatus($id)
    {
        Log::info('Attempting to toggle status for file: ' . $id);
        $file = File::find($id);

        if (!$file) {
            Log::error('File not found with ID: ' . $id);
            return response()->json(['error' => 'File not found.'], 404);
        }

        $file->status = !$file->status;
        $file->save();
        Log::info('Toggled status for file: ' . $id . ' to ' . $file->status);

        return response()->json(['success' => true, 'newStatus' => $file->status]);
    }

    private function detectFileChanges(File $file, array $validated): bool
    {
        return $file->name !== $validated['name'] ||
               $file->path !== $validated['file'] ||
               $file->auto_id !== $validated['auto_id'];
    }

}
