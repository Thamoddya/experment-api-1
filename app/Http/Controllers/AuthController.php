<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        /** @var User $user */
        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'email or Password Incorrect'
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('access_token')->plainTextToken;
        return response(compact('user','token'));
//        return response([
//            'user' => $user,
//            'token' => $token
//        ]);
    }

    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        /** @var User $user */
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        $token = $user->createToken('access_token')->plainTextToken;


        return response(compact('user', 'token'));
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response('', 204);
    }
}
