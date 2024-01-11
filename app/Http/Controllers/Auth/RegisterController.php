<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    public function index()
    {
        return response()->json(['message' => "страница регистрации"]);

    }


    public function register(UserRequest $request)
    {
        $data = $request->toArray();
        $user = User::create($data);

        if ($user) {
            Auth::login($user);

            return response()->json(['message' => "пользователь создан"]);
        }
        return response()->json(['message' => "не удалось создать пользователя"]);
    }

}
