<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthAlways
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем окружение
        if (app()->environment('local', 'development', 'testing')) {

            // Если пользователь не авторизован
            if (!Auth::check()) {
                // Получаем или создаем тестового пользователя
                $user = User::where('email', 'always@auth.com')->first();

                if (!$user) {
                    $user = User::create([
                        'name' => 'Always Auth User',
                        'email' => 'always@auth.com',
                        'password' => bcrypt('password'),
                        'active' => rand(0, 1),
                        'age' => rand(16, 63),
                        'slug' => Str::slug('name'),
                    ]);
                }

                Auth::login($user);
            }
        }

        return $next($request);
    }
}
