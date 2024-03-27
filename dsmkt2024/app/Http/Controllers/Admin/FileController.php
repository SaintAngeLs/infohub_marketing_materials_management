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
        if (!$request->hasFile('file') && $file->exists) {
            $this->updateFileAttributes($file, $validated, $request);
            $file->save();
            return;
        }

        $fileSource = $validated['file_source'];
        // Strategy initialization
        $strategy = null;

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
            case 'server_file':
                if (!empty($validated['server_file'])) {
                    $strategy = new UseServerFileStrategy();
                }
                break;
        }

        if ($strategy !== null) {
            $strategy->upload($request, $file, $validated);
            if ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
                $validated['extension'] = $uploadedFile->getClientOriginalExtension();
                $validated['weight'] = $uploadedFile->getSize(); // Gets the size in bytes
            }
            $this->updateFileAttributes($file, $validated, $request);
        } else {
            // Handle cases where no valid file is provided during update
            if ($file->exists && !$request->hasFile('file')) {
                // Potentially log this situation or handle it as appropriate
                // e.g., Log::info('No new file provided for update.');
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
            $file->weight = $validated['weight']; // You might want to convert this to a different unit
        }

        // Update the extension if a file is uploaded
        if ($request && $request->hasFile('file')) {
            $file->extension = $request->file('file')->getClientOriginalExtension();
        }

        // Other attributes as before...

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
