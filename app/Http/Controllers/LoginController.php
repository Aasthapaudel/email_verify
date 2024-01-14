<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller; // Check this line, make sure there are no typos
use App\Models\User; // Add the appropriate namespace for your User model

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        // Obtain the user information from GitHub
        $githubUser = Socialite::driver('github')->user();

        // Your logic to handle the GitHub user data
        // For example, you can check if the user exists in your database and log them in
        $user = User::where('github_id', $githubUser->getId())->first();

        if ($user) {
            // Log in the existing user
            \Auth::login($user);
        } else {
            // Create a new user account
            $newUser = User::create([
                'name' => $githubUser->getName(),
                'email' => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                // ... other fields
            ]);

            // Log in the new user
            \Auth::login($newUser);
        }

        // Redirect to the desired page after authentication
        return redirect()->route('home');
    }
}
