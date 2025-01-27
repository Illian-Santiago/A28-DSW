<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ServicesController extends Controller
{
    public function redirectToGithub(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback(): RedirectResponse
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $findUser = User::where('github_id', $githubUser->id)->first();

            if($findUser){
                Auth::login($findUser);
                return redirect('/dashboard');

            }else{
                $user = User::UpdateOrCreate(
                    ['github_id' => $githubUser->id],
                    [
                        'name' => $githubUser->name,
                        'nickname' => $githubUser->nickname,
                        'email' => $githubUser->email,
                        'avatar' => $githubUser->avatar,
                        'github_token' => $githubUser->token,
                        'github_refresh_token' => $githubUser->refreshToken,
                    ]
                );

                Auth::login($user);
                return redirect('/dashboard');
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $findUser = User::where('google_id', $googleUser->id)->first();

            if($findUser){
                Auth::login($findUser);
                return redirect('/dashboard');

            }else{
                $user = User::updateOrCreate(
                    ['email' => $googleUser->email],
                    [
                        'name' => $googleUser->name,
                        'google_id'=> $googleUser->id,
                        'password' => encrypt('123456dummy')
                    ]);

                Auth::login($user);
                return redirect('/dashboard');
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}