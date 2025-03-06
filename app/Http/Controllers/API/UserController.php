<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->success(
            $user,
            'User retrieved successfully',
            200
        );
    }

    public function show($id)
    {
        try {
            $user = User::findorfail($id);
            return response()->success(
                $user,
                'User detail',
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('User not found', 404);
        }return response()->json($user);
    }

    public function store(UserRequest $request)
    {
        try {
            $timesheet = User::create($request->validated());
            return response()->success(
                $timesheet,
                'User created successfully',
                201
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('User not found', 404);
        }
    }

    public function update(UserRequest $request,$id)
    {
        try {
            $timesheet = User::findOrFail($id);
            $timesheet->update($request->validated());

            return response()->success(
                $timesheet,
                'User updated successfully',
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('User not found', 404);
        }
        $user->update($request->validated());
        return response()->json($user);
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->success(
                [],
                'User deleted successfully',
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('User not found', 404);
        }
    }
}
