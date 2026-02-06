@extends('layouts.app')
@section('content')
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            @if(session()->has('success'))
                <div class="alert-message success animate__animated animate__fadeInDown">
                    <div class="alert-content">
                        <div class="alert-icon">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        </div>
                        <div class="alert-text">
                            <h4>Успешно!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <style>
                .alert-message {
                    position: relative;
                    margin: 1rem 0;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                }

                .alert-message.success {
                    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                    border-left: 5px solid #28a745;
                }

                .alert-content {
                    display: flex;
                    align-items: center;
                    padding: 1.25rem;
                    gap: 1rem;
                }

                .alert-icon {
                    flex-shrink: 0;
                    width: 50px;
                    height: 50px;
                }

                .checkmark__circle {
                    stroke-dasharray: 166;
                    stroke-dashoffset: 166;
                    stroke-width: 2;
                    stroke-miterlimit: 10;
                    stroke: #28a745;
                    fill: none;
                    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
                }

                .checkmark__check {
                    transform-origin: 50% 50%;
                    stroke-dasharray: 48;
                    stroke-dashoffset: 48;
                    stroke: #28a745;
                    stroke-width: 3;
                    stroke-linecap: round;
                    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
                }

                @keyframes stroke {
                    100% {
                        stroke-dashoffset: 0;
                    }
                }

                .alert-text {
                    flex-grow: 1;
                }

                .alert-text h4 {
                    margin: 0 0 5px 0;
                    color: #155724;
                    font-weight: 600;
                }

                .alert-text p {
                    margin: 0;
                    color: #155724;
                    opacity: 0.9;
                }

                .alert-close {
                    background: none;
                    border: none;
                    color: #155724;
                    opacity: 0.6;
                    cursor: pointer;
                    transition: opacity 0.3s;
                    padding: 5px;
                    border-radius: 50%;
                    width: 30px;
                    height: 30px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .alert-close:hover {
                    opacity: 1;
                    background: rgba(0,0,0,0.05);
                }
            </style>

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            Редактирование пользователя
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Изменение данных: {{ $user->name }}
                        </p>
                    </div>

                    <a href="{{ route('users.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700
                          hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200
                          font-medium rounded-lg transition-colors">
                        ← Назад к списку
                    </a>
                </div>
            </div>

            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                <div class="p-6 sm:p-8">

                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">

                            <!-- Аватар + имя -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                                <div class="flex-shrink-0">
                                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                            flex items-center justify-center text-white font-bold text-3xl shadow-md">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Имя пользователя
                                    </label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                              dark:bg-gray-700 dark:text-white shadow-sm
                                              focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                              @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">

                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email
                                </label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">

                                @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Пароль (опционально) -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Новый пароль <span class="text-gray-500 dark:text-gray-400 text-xs">(оставьте пустым, если не меняете)</span>
                                </label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       autocomplete="new-password"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">

                                @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Подтверждение пароля -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Подтверждение пароля
                                </label>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5">
                            </div>

                            <!-- Кнопки -->
                            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('users.index') }}"
                                   class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                                      text-gray-800 dark:text-gray-200 font-medium rounded-lg transition">
                                    Отмена
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700
                                           text-white font-medium rounded-lg shadow-md transition-all duration-200
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Сохранить изменения
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

        </div>
@endsection
