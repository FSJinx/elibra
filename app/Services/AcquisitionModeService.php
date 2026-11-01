<?php

namespace App\Services;

use App\Models\AcquisitionMode;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AcquisitionModeService
{
    private const FIELDS = ['id', 'name', 'description', 'has_price'];

    public function create(?array $data)
    {
        return DB::transaction(fn () => AcquisitionMode::create(Arr::only($data, self::FIELDS)));
    }
}
