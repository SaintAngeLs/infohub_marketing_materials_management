<?php

namespace App\Services;

use ZipArchive;
use App\Contracts\IFileService;
use App\Models\Auto;
use App\Models\File;
use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use App\Strategies\FileUpload\UploadFromPCStrategy;
use App\Strategies\FileUpload\UploadFromUrlStrategy;
use App\Strategies\FileUpload\UseServerFileStrategy;

class FileService implements IFileService
{
    public function getMenuItemsToSelect()
    {
        return MenuItem::all();
    }

    public function getAutos()
    {
        return Auto::all();
    }

    public function getServerFiles()
    {
        $files = Storage::disk('public')->files('menu_files');
        return array_map(function ($file) {
            return basename($file);
        }, $files);
    }

    public function validateRequest(Request $request, $isStore = true)
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

        switch ($request->input('file_source')) {
            case 'file_pc':
                $rules['file'] = 'required|file|max:716800'; // 700 MB
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

    public function handleFileUpload(Request $request, File &$file, array $validated)
    {
        if (!$request->hasFile('file') && $file->exists) {
            $this->updateFileAttributes($file, $validated, $request);
            $file->save();
            return;
        }

        Log::info('The request in the fileUpload', $request->all());
        $fileSource = $validated['file_source'];
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
                    Log::info("The strategy for the server file");
                }
                break;
        }
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
                Log::info("The request does not contain the file");
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

        if ($request && $request->hasFile('file')) {
            $file->extension = $request->file('file')->getClientOriginalExtension();
        }

        $userId = auth()->id();
        $file->add_by = $file->exists ? $file->add_by : $userId;
        $file->update_by = $userId;
    }

    public function updateFileModel(File &$file, array $validated)
    {
        Log::info('UpdatefileModel function', $file->toArray());
        $this->updateFileAttributes($file, $validated);
        $file->save();
        Log::info('File model updated:', $file->toArray());
    }

    public function deleteFile(File $file)
    {
        if ($file->path && Storage::exists($file->path)) {
            Storage::delete($file->path);
        }
        $file->delete();
    }

    public function downloadFile(File $file)
    {
        if ($file->file_source == 'file_external') {
            return redirect()->away($file->path);
        }

        $filePath = storage_path('app/public/' . $file->path);
        Log::debug('File path', ['path' => $filePath]);
        Log::info('File path');
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath), [
            'Content-Type' => mime_content_type($filePath)
        ]);
    }

    public function toggleStatus(File $file)
    {
        $file->status = !$file->status;
        $file->save();
        return $file->status;
    }

    public function detectFileChanges(File $file, array $validated): bool
    {
        Log::info("Validated data in the detectFileChanges is", $validated);

        $hasChanges = false;

        if ($file->name !== $validated['name'] || $file->auto_id !== (isset($validated['auto_id']) ? $validated['auto_id'] : null)) {
            $hasChanges = true;
        }

        switch ($validated['file_source']) {
            case 'file_pc':
                if (isset($validated['file']) || $validated['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $hasChanges = true;
                }
                break;
            case 'file_external':
                if (isset($validated['file_url']) || $file->path !== $validated['file_url']) {
                    $hasChanges = true;
                }
                break;
            case 'file_server':
                if (isset($validated['server_file']) || $file->path !== $validated['server_file']) {
                    $hasChanges = true;
                }
                break;
            default:
                Log::warning("Unknown file source: " . $validated['file_source']);
                break;
        }

        return $hasChanges;
    }

    public function downloadMultipleFiles(Request $request)
    {
        $fileIds = $request->input('files');
        if (empty($fileIds)) {
            return redirect()->back()->with('error', 'No files selected.');
        }

        $files = File::whereIn('id', $fileIds)->get();
        if ($files->isEmpty()) {
            return redirect()->back()->with('error', 'No files found.');
        }

        $zip = new ZipArchive;
        $zipFileName = 'downloads_' . time() . '.zip';
        $zipPath = public_path('downloads/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file->path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();

            return response()->download($zipPath);
        } else {
            return redirect()->back()->with('error', 'Failed to create zip file.');
        }
    }

    public function getDirectoryStructure()
    {
        $specificFolderPath = storage_path('app/public/ftp_upload');
        $secureStructure = [];

        if (FileFacade::isDirectory($specificFolderPath)) {
            $files = FileFacade::files($specificFolderPath);
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
}
