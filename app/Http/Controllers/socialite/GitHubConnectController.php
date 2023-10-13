<?php

namespace App\Http\Controllers\socialite;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GitHubConnectController extends Controller
{
    public function connect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callBack()
    {
        $githubUser = Socialite::driver('github')->stateless()->user();

        $user = User::where('email', $githubUser->email)->first();
        if ($user) {
        } else {
            $user = User::updateOrCreate([
                'github_id' => $githubUser->id,
            ], [
                'name' => $githubUser->name,
                'email' => $githubUser->email,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        }
        Auth::login($user);


        return redirect('/');
    }
}
