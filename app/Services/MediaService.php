<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    private function folder(string $imageType): string
    {
        return match ($imageType) {
            Media::PROFILE   => 'media/profiles',
            Media::LOGO      => 'media/logos',
            Media::BOOK_COVER=> 'media/books',
            Media::DOCUMENT  => 'media/documents',
            Media::BANNER    => 'media/banners',
            Media::OTHER     => 'media/others',
        };
    }

    public function store(UploadedFile $file, string $imageType): Media
    {
        $path = $file->store($this->folder($imageType), 'public');

        return Media::create([
            'file_name'  => $file->getClientOriginalName(),
            'file_path'  => $path,
            'mime_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(),
            'image_type' => $imageType,
        ]);
    }

    public function replaceFile(Media $media, UploadedFile $file): Media
    {
        Storage::disk('public')->delete($media->file_path);

        $path = $file->store($this->folder($media->image_type), 'public');

        $media->update([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return $media->fresh();
    }

    public function delete(Media $media): void
    {
        Storage::disk('public')->delete($media->file_path);

        $media->delete();
    }
}