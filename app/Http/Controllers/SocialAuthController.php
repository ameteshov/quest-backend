<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\JWTAuth;

class SocialAuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function googleLoginCallback(Request $request, UserService $service, JWTAuth $auth)
    {
        $token = $service->createOrLoginFormSocial(Socialite::driver('google')->stateless()->user(), 'google');

        return redirect(config('frontend_url') . "/complete-auth?token={$token}");
    }
}
