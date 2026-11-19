<?php

namespace App\Enums;

enum ReportType: string
{
    case Circulation = 'circulation';
    case User = 'user';
    case Attendance = 'attendance';
    case Reservation = 'reservation';
    case Item = 'item';
    case Acquisition = 'acquisition';

    public function folder(): string
    {
        return $this->value;
    }

    public function title(): string
    {
        $name = match ($this) {
            self::Circulation => 'Circulation',
            self::User => 'User',
            self::Attendance => 'Attendance',
            self::Reservation => 'Reservation',
            self::Item => 'Collection',
            self::Acquisition => 'Acquisition',
        };

        return now()->year." $name Report";
    }

    public function filters(): array
    {
        return match ($this) {
            self::Circulation => [],
            self::User => ['roles'],
            default => []
        };
    }

    /**
     * Returns default columns that will be automatically included when generating a report or backup
     */
    public function columns(): array
    {
        return match ($this) {
            self::Circulation => ['id', 'accession_number', 'title', 'borrower', 'patron_type', 'borrowed_at', 'due_at', 'returned_at', 'status'],
            self::User => ['id', 'last_name', 'first_name', 'middle_initial', 'sex', 'role', 'created_at'],
            self::Attendance => ['id', 'patron', 'patron_type', 'username', 'status', 'section', 'library', 'created_at'],
            self::Reservation => ['id', 'accession_number', 'title', 'patron', 'status', 'expected_pickup_date', 'pickup_date', 'created_at'],
            self::Item => ['id', 'title', 'call_number', 'item_type', 'category', 'isbn_issn', 'publication_year', 'library', 'created_at'],
            self::Acquisition => ['id', 'reference', 'dealer', 'acquisition_date', 'acquisition_mode', 'receiver', 'remarks', 'created_at'],
            default => [],
        };
    }
}
