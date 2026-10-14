<?php

namespace App\Http\Controllers\UI;

use App\Http\Controllers\Controller;
use App\Models\Sections;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function sections()
    {
        $sections = Sections::query()->select(['id', 'name', 'library_id'])->get();

        return $this->response(data: $sections);
    }
}
