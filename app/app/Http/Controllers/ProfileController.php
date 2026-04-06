<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Показать профиль пользователя.
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Обновить профиль пользователя.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Если есть хоть какое-то поле пароля - это режим смены пароля
        $isPasswordChange = $request->has('current_password') || $request->has('new_password');

        $rules = [
            'current_password' => 'required_with:new_password',
            'new_password' => $request->has('current_password') ? 'required|min:6|confirmed' : 'nullable|min:6|confirmed',
        ];

        if ($isPasswordChange) {
            // При смене пароля name/username/email необязательны
            $rules['name'] = 'nullable|string|max:255';
            $rules['username'] = 'nullable|string|max:255';
            $rules['email'] = 'nullable|email|unique:users,email,' . $user->id;
        } else {
            // При обычном обновлении профиля name/username/email обязательны
            $rules['name'] = 'required|string|max:255';
            $rules['username'] = 'required|string|max:255';
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        $messages = [
            'new_password.required' => 'Поле новый пароль обязательно.',
            'current_password.required_with' => 'Поле текущий пароль обязательно.',
            'name.required' => 'Поле имя обязательно.',
            'username.required' => 'Поле имя пользователя обязательно.',
            'email.required' => 'Поле email обязательно.',
            'email.email' => 'Введите корректный email.',
            'new_password.min' => 'Пароль должен быть не менее :min символов.',
            'new_password.confirmed' => 'Пароли не совпадают.',
            'confirmed' => 'Пароли не совпадают.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('username')) {
            $user->username = $request->username;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Текущий пароль неверен.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Профиль обновлен.');
    }

    /**
     * Показать уведомления пользователя.
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(20);
        return view('profile.notifications', compact('notifications'));
    }

    /**
     * Отметить уведомление как прочитанное.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);
        return back()->with('success', 'Уведомление прочитано.');
    }

    /**
     * Отметить все уведомления как прочитанные.
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->update(['is_read' => true]);
        return back()->with('success', 'Все уведомления прочитаны.');
    }
}
