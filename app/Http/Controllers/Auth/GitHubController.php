<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {

        try {

            // Ottieni i dettagli dell'utente da GitHub
            $user = Socialite::driver('github')->user();
            // Cerca l'utente nel database in base all'ID di GitHub
            $finduser = User::where('github_id', $user->id)->first();
            if ($finduser) {
                // Se l'utente esiste, autenticalo
                Auth::login($finduser);

                return redirect()->intended('dashboard');
            } else {
                // Se l'utente non esiste, crea un nuovo utente
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'github_id' => $user->id,
                    'password' => Hash::make(uniqid())
                ]);

                // Autentica il nuovo utente
                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Something went wrong, please try again.');
        }
    }
}
