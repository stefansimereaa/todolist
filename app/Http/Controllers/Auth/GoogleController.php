<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {

            // $user = Socialite::driver('google')->stateless()->user();
            $user = Socialite::driver('google')->user();
            $findUser = User::where('google_id', $user->id)->first();


            if ($findUser) {

                Auth::login($findUser);


                return redirect()->intended('dashboard');
            } else {

                // $newUser = User::create([
                //     'name' => $user->name,
                //     'email' => $user->email,
                //     'google_id' => $user->id,
                //     'password' => Hash::make(uniqid())
                // ]);

                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'google_id' => $user->getId(),
                    'password' => Hash::make(uniqid())
                ]);

                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Something went wrong, please try again.');
        }
    }
}
