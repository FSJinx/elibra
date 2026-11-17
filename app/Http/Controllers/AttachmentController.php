<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Attachment $attachment)
    {
        $disk = Storage::disk($attachment->disk);

        abort_unless($disk->exists($attachment->path), 404);

        return response()->streamDownload(
            fn () => fpassthru($disk->readStream($attachment->path)),
            $attachment->original_name,
            ['Content-Type' => $attachment->mime_type],
        );
    }
}
