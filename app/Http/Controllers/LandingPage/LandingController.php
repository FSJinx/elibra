<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Item;

class LandingController extends Controller
{
    public function newItems()
    {
        return $this->response(data: Item::latest()->limit(6)->get()->toArray());
    }

    public function discover()
    {
        return $this->response(data: Item::with(['itemType', 'itemTypeCategory', 'authors'])
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->toArray());
    }
}
