<?php

namespace App\Services;

use App\Enums\Enums\AttachmentEnums;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttachmentService
{
    public function store(AttachmentEnums $type, UploadedFile $file): Attachment
    {
        Validator::validate($file, $type->rules());
        
        return Attachment::create([
            'disk' => $type->disk(),
            'collections' => $type->value,
            'path' => $file->store($type->folder(), $type->disk()),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),

            'uploaded_id' => Auth::id(),
        ]);
    }
}
