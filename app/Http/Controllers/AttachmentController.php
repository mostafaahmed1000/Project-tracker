<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {

        try {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,png,pdf,docx,txt'
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', 'Invalid file type or size.');
        }
        $file = $request->file('file');
        $path = $file->store('attachments');
        Attachment::create([
            'task_id' => $request->task_id,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'uploader_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'File uploaded.');
    }

    public function destroy(Attachment $attachment)
    {

        Storage::delete($attachment->file_path);
        $attachment->delete();

        return redirect()->back()->with('success', 'Attachment deleted.');
    }

    public function download(Attachment $attachment)
    {
        return Storage::download($attachment->file_path, $attachment->original_name);
    }
}
