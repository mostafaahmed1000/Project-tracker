<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Attachment;
use App\Http\Resources\AttachmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class AttachmentApiController extends Controller
{
    use AuthorizesRequests;
    public function index(Task $task)
    {
        $this->authorize('view', $task);
        return AttachmentResource::collection($task->attachments);
    }

    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,png,pdf,docx,txt'
        ]);
        

        $file = $request->file('file');
        $path = $file->store('attachments');
        
        $attachment = $task->attachments()->create([
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'uploader_id' => Auth::id(),
        ]);

        return new AttachmentResource($attachment);
    }

    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);
        Storage::delete($attachment->file_path);
        $attachment->delete();

        return response()->json(null, 204);
    }
}
