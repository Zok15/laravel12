<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Попытка аутентификации
        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The credentials you entered are incorrect.']
            ]);
        }

        // Важный шаг: регенерация сессии
        $request->session()->regenerate();

        // Возвращаем пользователя, чтобы фронтенд понимал, кто залогинен
        return response()->json([
            'user' => Auth::user()
        ]);
    }
}
