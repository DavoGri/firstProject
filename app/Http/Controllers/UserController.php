<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Passport\HasApiTokens;

class UserController extends Controller
{
    use HasApiTokens;


    public function show($user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            return response()->json($user);

        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'пользователь не найден'], 404);
        }

    }
    public function update(UserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            $this->authorize('update',$user);

            $user->update($request->validated());
            return response()->json(['message' => 'данные пользователя успешно обновлены']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'пользователь не найден'], 404);
        }


    }


    public function delete($user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            $this->authorize('delete',$user);

            $user->delete();
            return response()->json(['message' => 'пользователь успешно удален']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'пользователь не найден'], 404);
        }
    }



}


