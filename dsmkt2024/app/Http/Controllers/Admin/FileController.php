<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auto;
use App\Models\File;
use App\Models\MenuItems\MenuItem;

use App\Strategies\FileUpload\UploadFromPCStrategy;
use App\Strategies\FileUpload\UploadFromUrlStrategy;
use App\Strategies\FileUpload\UseServerFileStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function create(Request $request)
    {
        $menuItemsToSelect = MenuItem::all();
        $autos = Auto::all();
        $serverFiles = $this->getServerFiles();

        return view('admin.files.create', compact('menuItemsToSelect', 'autos', 'serverFiles'));
    }

    public function edit($fileId)
    {
        $file = File::findOrFail($fileId);
        $menuItemsToSelect = MenuItem::all();
        $autos = Auto::all();
        $serverFiles = $this->getServerFiles();

        return view('admin.files.edit', compact('file', 'menuItemsToSelect', 'autos', 'serverFiles'));
    }

    public function store(Request $request)
    {
        Log::info('Store request ',  $request->all());
        $validated = $this->validateRequest($request, true);

        try {
            $file = new File();
            $this->handleFileUpload($request, $file, $validated);
            $this->updateFileModel($file, $validated);

            return back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, File $file)
    {
        $validated = $this->validateRequest($request, false);

        try {
            $this->handleFileUpload($request, $file, $validated);
            $this->updateFileModel($file, $validated);

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
            'file_source' => 'required|string',
        ];

        if ($isStore) {
            $rules['file'] = 'required|file|max:716800'; // 700 MB
        } else {
            $rules['file'] = 'sometimes|file|max:716800';
        }

        return $request->validate($rules);
    }

    private function handleFileUpload(Request $request, File &$file, array $validated)
    {
        // Determine the source of the file based on the 'file_source' value from the request.
        $fileSource = $validated['file_source'];

        // Log::error('The file source is:', $fileSource);
        // Log::info('Validated request value:', $validated);
        // Initialize strategy variable
        $strategy = null;

        Log::info('Handle file input is:', $request->all());

        // Determine which strategy to use based on the source
        switch ($fileSource) {
            case 'file_pc':

                    $strategy = new UploadFromPCStrategy();

                break;
            case 'file_external':
                // Set strategy for handling external URL uploads
                if (!empty($validated['file_url'])) {
                    $strategy = new UploadFromUrlStrategy();
                }
                break;
            case 'server_file':
                // Set strategy for using server files
                if (!empty($validated['server_file'])) {
                    $strategy = new UseServerFileStrategy();
                }
                break;
        }

        // Execute the strategy if set
        if ($strategy !== null) {
            $strategy->upload($request, $file, $validated);
        } else {
            throw new \Exception("No valid file upload source provided.");
        }
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
        foreach ($validated as $key => $value) {
            if ($key !== 'file') {
                $file->$key = $value ?? null;
            }
        }
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
}
