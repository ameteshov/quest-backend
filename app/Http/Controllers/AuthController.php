<?php

namespace App\Http\Controllers;

use App\Request\ConfirmPasswordRequest;
use App\Request\LoginRequest;
use App\Request\LoginSocialRequest;
use App\Request\RegisterRequest;
use App\Request\ResetPasswordRequest;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
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

    public function register(RegisterRequest $request, UserService $userService, JWTAuth $auth)
    {
        $user = $userService->create($request->except(['confirm']), false);

        $token = $auth->fromUser($user);

        return response()->json([
            'user' => $user->toArray(),
            'token' => $token,
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl')
        ]);
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

    public function socialLogin(LoginSocialRequest $request, $provider)
    {
        return response()->json([
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function googleLoginCallback(Request $request, UserService $service, JWTAuth $auth)
    {
        $token = $service->createOrLoginFormSocial(Socialite::driver('google')->stateless()->user(), 'google');

        return redirect(config('defaults.frontend_url') . "/complete-social-auth?token={$token}");
    }

    public function vkLoginCallback(Request $request, UserService $service, JWTAuth $auth)
    {
        $token = $service->createOrLoginFormSocial(Socialite::driver('vkontakte')->stateless()->user(), 'vkontakte');

        return redirect(config('defaults.frontend_url') . "/complete-social-auth?token={$token}");
    }

    public function facebookLoginCallback(Request $request, UserService $service, JWTAuth $auth)
    {
        $token = $service->createOrLoginFormSocial(Socialite::driver('facebook')->stateless()->user(), 'facebook');

        return redirect(config('defaults.frontend_url') . "/complete-social-auth?token={$token}");
    }
}
