<?php

namespace App\Http\Controllers\UI;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\PatronType;
use App\Models\Sections;

class OptionController extends Controller
{
    public function campuses()
    {
        $campuses = Campus::query()->select(['id', 'name'])->get();

        return $this->response(data: $campuses);
    }

    public function sections()
    {
        $sections = Sections::query()->select(['id', 'name', 'library_id'])->get();

        return $this->response(data: $sections);
    }

    public function patronTypes()
    {
        $types = PatronType::query()->select(['id', 'key', 'name', 'has_campus', 'has_department', 'has_program'])->get();

        return $this->response(data: $types);
    }
}
