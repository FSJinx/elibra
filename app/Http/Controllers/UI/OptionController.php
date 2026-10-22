<?php

namespace App\Http\Controllers\UI;

use App\Http\Controllers\Controller;
use App\Models\Authorship;
use App\Models\Campus;
use App\Models\Department;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use App\Models\Language;
use App\Models\Library;
use App\Models\PatronType;
use App\Models\Programs;
use App\Models\Sections;

class OptionController extends Controller
{
    /**
     *  =================== ORGANIZATIONS ==================
     */
    public function campuses()
    {
        $data = Campus::query()->select(['id', 'name'])->get();

        return $this->response(data: $data);
    }

    public function departments()
    {
        $data = Department::query()->select(['id', 'name', 'campus_id'])->get();

        return $this->response(data: $data);
    }

    public function libraries()
    {
        $data = Library::query()->select(['id', 'name'])->get();

        return $this->response(data: $data);

    }

    public function programs()
    {
        $data = Programs::query()->select(['id', 'name', 'department_id'])->get();

        return $this->response(data: $data);

    }

    public function sections()
    {
        $data = Sections::query()->select(['id', 'name', 'library_id'])->get();

        return $this->response(data: $data);
    }

    /**
     *  =================== STANDARDS ==================
     */
    public function authorships()
    {
        $data = Authorship::query()->select(['id', 'slug', 'name', 'item_type_id'])->get();

        return $this->response(data: $data);
    }

    public function categories()
    {
        $data = ItemTypeCategory::query()->select(['id', 'code', 'name', 'item_type_id'])->get();

        return $this->response(data: $data);
    }

    public function item_types()
    {
        $data = ItemType::query()->select(['id', 'slug', 'name', 'has_electronic_file'])->get();

        return $this->response(data: $data);
    }

    public function patronTypes()
    {
        $data = PatronType::query()->select(['id', 'key', 'name', 'has_campus', 'has_department', 'has_program'])->get();

        return $this->response(data: $data);
    }

    /**
     *  ================ AUTHORITY CONTROL ==============
     */
    public function languages()
    {
        $data = Language::query()->select(['id', 'code', 'name'])->get();

        return $this->response(data: $data);

    }
}
