<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $validated = $request->validated();

        $user  = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
