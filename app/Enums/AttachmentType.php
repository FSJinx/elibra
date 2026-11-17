<?php

namespace App\Enums;

enum AttachmentType: string
{
    case ProfilePicture = 'profile_picture';
    case CoverPhoto = 'cover_photo';
    case ElectronicFile = 'electronic_file';
    case Report = 'reports';

    /**
     * Returns the disk of the attachment
     */
    public function disk(): string
    {
        return in_array($this, [
            self::ProfilePicture,
            self::CoverPhoto,
            self::ElectronicFile,
        ], true) ? 'public' : 'local';
    }

    public function folder(): string
    {
        return $this->value.'s';
    }

    public function rules(): array
    {
        return match ($this) {
            self::ProfilePicture => ['required', 'image', 'mimes:jpg,png,webp', 'max:2048'],
            self::CoverPhoto => ['required', 'image', 'mimes:jpg,png,webp', 'max:2048'],
            self::ElectronicFile => ['required', 'file', 'mimes:pdf', 'max:51200'],
            default => [],
        };
    }
}
