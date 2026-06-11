<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function index()
    {
        dd(get_class_methods(auth('api')->refresh()));
    }
}
