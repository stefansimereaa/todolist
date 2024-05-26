<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required', 'email', 'max:255',
            'password' => 'required', 'min:8',
        ], [
            'email.required' => 'Il campo email è obbligatorio.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.max' => 'Il campo email non può superare :max caratteri.',
            'password.required' => 'Il campo password è obbligatorio.',
            'password.min' => 'La password deve essere lunga almeno :min caratteri.',
        ]);


        $user = User::create([
            // 'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
    // public function loginOrRegister(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'email' => ['required', 'email', 'max:255'],
    //         'password' => ['required', Rules\Password::defaults()],
    //     ], [
    //         'email.required' => 'Il campo email è obbligatorio.',
    //         'email.email' => 'Inserisci un indirizzo email valido.',
    //         'email.max' => 'Il campo email non può superare :max caratteri.',
    //         'password.required' => 'Il campo password è obbligatorio.',
    //         'password.*' => 'La password fornita non è valida.',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if ($user) {
    //         // If the user exists, attempt to login
    //         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //             return redirect()->intended(RouteServiceProvider::HOME);
    //         } else {
    //             // If login attempt fails, redirect back with error
    //             return redirect()->back()->with('error', 'Invalid credentials.');
    //         }
    //     } else {
    //         // If the user does not exist, create a new one and login
    //         $newUser = User::create([
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         Auth::login($newUser);

    //         return redirect()->intended(RouteServiceProvider::HOME);
    //     }
    // }
}
