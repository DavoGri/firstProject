<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuperAdminRequest;
use App\Http\Resources\UserResource;
use App\Models\User;



class SuperAdminController extends Controller
{
    public function create(SuperAdminRequest $request)
    {
        // Проверка, что суперадмин еще не создан
        if (User::where('super_admin_created', true)->exists()) {
            return redirect()->back()->with('error', 'Суперадмин уже создан.');
        }

        if ($request->input('email') !== 'davgriart@mail.ru') {
            return response()->json(['message'=> 'вы не прошли проверку.']);
        }


        $superAdmin = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'role'=>'super_admin',
            'super_admin_created' => true,

        ]);


        return response()->json(['message'=> 'Суперадмин успешно создан.']);
    }

    public function createAdmin(SuperAdminRequest $request)
    {
        // Проверка, что текущий пользователь имеет роль 'super_admin'
        if ($request->user()->role !== 'super_admin') {
            return response()->json(['message' => 'У вас нет прав для создания админов.'], 403);
        }


        $admin= User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'role' => 'admin'
        ]);

        return new UserResource($admin);
    }
}

