<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Регистрация пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $feilds = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create($feilds);

        // Отправляем событие о регистрации пользователя
        event(new Registered($user));

        // Возвращаем ответ в формате JSON
        return response()->json([
            'user' => $user,
        ], 200);

    }

    /**
     * Аутентификация пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $feilds = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|'
        ]);

        // Пытаемся авторизоваться
        if (!Auth::attempt($feilds)) {
            // Если авторизация не удалась, возвращаем ошибку
            return response()->json(['errors' => [
                'user' => ['неверные учетные данные']
            ]], 401);
        }

        // Регенерируем сессию
        $request->session()->regenerate();

        // Возвращаем ответ в формате JSON
        return response()->json(['message' => 'Успешно авторизован', 'user' => Auth::user()]);
    }

    /**
     * Выход из системы
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request){
        // Выходим из системы
        Auth::logout();

        // Инвалидируем сессию
        $request->session()->invalidate();

        // Регенерируем новый токен сессии
        $request->session()->regenerateToken();

        // Возвращаем ответ в формате JSON
        return response()->json(['message' => 'Успешно вышел из системы']);
    }
}