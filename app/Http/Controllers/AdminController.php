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

            // UserService::adminPermissions($admin);

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
                'error',
                'Failed to create admin.',
                [],
                422,
            );
       }
    }

    public function update(UpdateAdminRequest $request, User $admin)
    {
        DB::beginTransaction();
        try{
            $data = $request->validated();

            //hash password if its included from the field
            if(isset($data['password'])){
                $data['password'] = Hash::make($data['password']);
            }

            $admin->update($data);

            DB::commit();

            return $this->response(
                'success',
                'Admin profile successfully updated',
                $admin->toArray(),
                200,
            );

        }catch(Exception $e){
            DB::rollback();

            return $this->response(
                'error',
                'Failed to update admin profile',
                [], 200,
            );
        }
    }

    public function destroy(User $admin)
    {
        $this->authorize('delete', $admin);

        DB::beginTransaction();
        try{
            $admin->delete();   

            DB::commit();

            return $this->response(
                'success',
                'Admin successfully deleted.',
                [],
                200
            );
        }catch(Exception $e){
            DB::rollBack();

            return $this->response(
                'error',
                'Failed to delete admin.',
                [],
                422
            );
        }
    }
}
