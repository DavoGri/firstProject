<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     public function index()
     {
         return response()->json(['message' => "страница входа"]);

     }


    public function login(LoginRequest $request)
    {
        if (Auth::check()) {
            return response()->json(['message' => "пользователь аутентифицирован"]);
        }
        $formFields = $request->all();
        if (Auth::attempt($formFields)) {
            $user = $request->user();
            $token = auth()->user()->createToken('Api Token')->accessToken;

            return response(['user'=>$user,'access_token'=>$token],200);
        }

        return response()->json(['message' => "не удалось аутентифицироваться"],401);

    }


    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully']);
    }

}
