<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function store(StoreAdminRequest $request)
    {
       DB::beginTransaction();
       try{
            $data = $request->validated();

            $data['password'] = Hash::make($data['password']);

            $admin = User::create($data);

            UserService::adminPermissions($admin);

            DB::commit();

            return $this->response(
                'success',
                'Admin successfully created.',
                $admin->toArray(),
                201,
            );

       } catch (Exception $e) {
            DB::rollback();

            return $this->response(
                'success',
                'Admin successfully created.',
                [],
                422,
            );
       }

    }

    public static function dashboard () {
        
    }
}
