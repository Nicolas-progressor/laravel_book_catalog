<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /**
     * Показать форму регистрации.
     */
    public function register()
    {
        return view('registration.register');
    }

    /**
     * Обработать регистрацию.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Пароли не совпадают.',
            'password.required' => 'Поле пароль обязательно.',
            'password.min' => 'Пароль должен быть не менее :min символов.',
            'email.required' => 'Поле email обязательно.',
            'email.email' => 'Введите корректный email.',
            'email.unique' => 'Этот email уже занят.',
            'username.required' => 'Поле имя пользователя обязательно.',
            'name.required' => 'Поле имя обязательно.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'roles' => ['ROLE_USER'],
        ]);

        auth()->login($user);

        return redirect('/')->with('success', 'Регистрация успешна!');
    }
}
