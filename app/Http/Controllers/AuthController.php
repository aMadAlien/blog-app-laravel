<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'register'
            ]
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password
        ]);
        auth()->login($user);
        return self::respondWithToken();
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login Failed'
            ]);
        }
        return self::respondWithToken();
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function respondWithToken()
    {
        return response()->json([
            'access_token' => auth()->refresh(),
            'type' => 'Bearer',
            'expires_in' => \Config::get('jwt.ttl')
        ]);
    }
}
