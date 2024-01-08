<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function registerUser(UserRequest $request)
    {
        $data = $request->toArray();
        $user = User::create($data);

        if ($user) {
            Auth::login($user);
            return response()->json(['message' => "пользователь создан"]);
        }
        return response()->json(['message' => "не удалось создать пользователя"]);
    }


    public function loginUser(LoginRequest $request)
    {
        if (Auth::check()) {
            return response()->json(['message' => "пользователь аутентифицирован"]);
        }
        $formFields = $request->all();
        if (Auth::attempt($formFields)) {
            return response()->json(['message' => "пользователь аутентифицирован!!"]);
        }

        return response()->json(['message' => "не удалось аутентифицироваться"]);

    }


    public function update(UserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->update($request->all());
            return response()->json(['message' => 'данные пользователя успешно обновлены']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'пользователь не найден'], 404);
        }


    }


    public function delete($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->delete();
            return response()->json(['message' => 'пользователь успешно удален']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'пользователь не найден'], 404);
        }
    }


    public function show($user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            return response()->json($user);

        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'пользователь не найден'], 404);
        }

    }
}


