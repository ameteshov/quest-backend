<?php

namespace App\Http\Controllers;

use App\Request\ConfirmPasswordRequest;
use App\Request\LoginRequest;
use App\Request\RegisterRequest;
use App\Request\ResetPasswordRequest;
use App\Service\UserService;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request, JWTAuth $auth, UserService $userService)
    {
        $token = $auth->attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if ($token === false) {
            return response()->json([
                'message' => 'Authorization failed'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userId = $auth->toUser($token)->id;

        $user = $userService->find($userId);

        return response()->json([
            'token' => $token,
            'user' => $user,
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl')
        ]);
    }

    public function register(RegisterRequest $request, UserService $userService)
    {
        $userService->create($request->except(['confirm']));

        return \response()->json('', Response::HTTP_OK);
    }

    public function resetPassword(ResetPasswordRequest $request, UserService $userService)
    {
        $userService->resetPassword($request->input('email'));

        return \response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function confirmPassword(ConfirmPasswordRequest $request, UserService $userService)
    {
        $userService->confirmPassword($request->all());

        return \response()->json('', Response::HTTP_NO_CONTENT);
    }
}
