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
use Inertia\Inertia;
use Inertia\Response;

//Blade用
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create2(): Response
    {
        return Inertia::render('Auth/Register');
    }
    //Blade用
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'postal_code' => $request->postalCode,
            'address_1' => $request->address1,
            'address_2' => $request->address2,
            'address_3' => $request->address3,
            'phone_number' => $request->phoneNumber,
        ]);

        event(new Registered($user));

        Auth::login($user);

        //カートからリダイレクトされた場合
        if (session()->has('redirect_to_reg')) {

            //セッションに保存しているルートにリダイレクトさせる
            return redirect(session()->pull('redirect_to_reg'));
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
