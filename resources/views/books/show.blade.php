@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $book->title }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Информация о книге
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    @auth
                        @if(auth()->id() === $book->user_id || auth()->user()?->is_admin)
                            <a href="{{ route('books.edit', $book) }}"
                               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700
                                      text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Редактировать
                            </a>
                        @endif
                    @endauth
                        <a href="{{ route('books.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700
                              hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200
                              font-medium rounded-lg transition-colors">
                        ← Назад к списку
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash сообщения -->
        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800
                        rounded-lg text-green-700 dark:text-green-400">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Основной контент -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Левая колонка - Обложка и основная информация -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">

                    <!-- Обложка книги -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-full relative mb-4">
                            @if($book->cover_image)
                                <div class="h-64 w-full rounded-lg overflow-hidden shadow-xl">
                                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                                         alt="{{ $book->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="h-64 bg-gradient-to-br from-blue-500 to-cyan-600
                                            rounded-lg shadow-xl flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-20 h-20 mx-auto text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <p class="text-white text-sm mt-2">Нет обложки</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">{{ $book->title }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1 text-center">{{ $book->author }}</p>
                    </div>

                    <!-- Мета-информация -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Детали</h3>

                        <div class="space-y-3">
                            @if($book->isbn)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">ISBN:</span>
                                    <span class="ml-auto text-gray-900 dark:text-white">
                                        {{ $book->isbn }}
                                    </span>
                                </div>
                            @endif

                            @if($book->pages)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span class="font-medium">Страниц:</span>
                                    <span class="ml-auto text-gray-900 dark:text-white">
                                        {{ $book->pages }}
                                    </span>
                                </div>
                            @endif

                            @if($book->published_year)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">Год издания:</span>
                                    <span class="ml-auto text-gray-900 dark:text-white">
                                        {{ $book->published_year }}
                                    </span>
                                </div>
                            @endif

                            @if($book->publisher)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-medium">Издательство:</span>
                                    <span class="ml-auto text-gray-900 dark:text-white">
                                        {{ $book->publisher }}
                                    </span>
                                </div>
                            @endif

                            @if($book->language)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Язык:</span>
                                    <span class="ml-auto text-gray-900 dark:text-white">
                                        {{ $book->language === 'ru' ? 'Русский' : 'Английский' }}
                                    </span>
                                </div>
                            @endif

                            @if($book->price)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Цена:</span>
                                    <span class="ml-auto text-gray-900 dark:text-white font-bold">
                                        {{ number_format($book->price, 2, '.', ' ') }} ₽
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Информация о создании -->
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Добавлена:</span>
                                <span class="ml-auto text-gray-900 dark:text-white">
                                    {{ $book->created_at->format('d.m.Y') }}
                                </span>
                            </div>

                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium">Обновлена:</span>
                                <span class="ml-auto text-gray-900 dark:text-white">
                                    {{ $book->updated_at->format('d.m.Y H:i') }}
                                </span>
                            </div>

                            @if($book->views_count)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="font-medium">Просмотров:</span>
                                    <span class="ml-auto text-gray-900 dark:text-white">
                                        {{ $book->views_count }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Правая колонка -->
            <div class="lg:col-span-2">
                <!-- Владелец книги -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Владелец книги</h3>

                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        @if($book->user)
                            <div class="flex-shrink-0">
                                @if($book->user->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $book->user->avatar->path)))
                                    <img src="{{ asset($book->user->avatar->path) }}"
                                         alt="{{ $book->user->name }}"
                                         class="h-16 w-16 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                                flex items-center justify-center text-white text-2xl font-bold">
                                        {{ strtoupper(mb_substr($book->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $book->user->name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $book->user->email }}
                                </div>
                                <a href="{{ route('users.show', $book->user) }}"
                                   class="inline-flex items-center mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Посмотреть профиль
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">Владелец книги был удален</p>
                        @endif
                    </div>
                </div>

                <!-- Описание книги -->
                @if($book->description)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Описание</h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $book->description }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Список пользователей, у которых есть эта книга -->
{{--                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">--}}
{{--                    <div class="flex items-center justify-between mb-6">--}}
{{--                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">--}}
{{--                            У кого есть эта книга--}}
{{--                        </h3>--}}
{{--                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">--}}
{{--                            {{ $book->count() }} {{ trans_choice('читатель|читателя|читателей', $book->count()) }}--}}
{{--                        </span>--}}
{{--                    </div>--}}

{{--                    @if($book->user)--}}
{{--                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">--}}
{{--                            @foreach($book->user as $book->user)--}}
{{--                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">--}}
{{--                                    <div class="flex-shrink-0">--}}

{{--                                        @if($book->user->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $book->user->avatar->path)))--}}
{{--                                            <img src="{{ asset($book->user->avatar->path) }}"--}}
{{--                                                 alt="{{ $book->user->name }}"--}}
{{--                                                 class="h-10 w-10 rounded-full object-cover">--}}
{{--                                        @else--}}
{{--                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600--}}
{{--                                                        flex items-center justify-center text-white text-sm font-bold">--}}
{{--                                                {{ strtoupper(mb_substr($book->user->name, 0, 1)) }}--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="ml-3 flex-1">--}}
{{--                                        <div class="text-sm font-medium text-gray-900 dark:text-white">--}}
{{--                                            {{ $book->user->name }}--}}
{{--                                        </div>--}}
{{--                                        <div class="text-xs text-gray-500 dark:text-gray-400">--}}
{{--                                            {{ $book->user->email }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <a href="{{ route('users.show', $book->user) }}"--}}
{{--                                       class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"--}}
{{--                                       title="Посмотреть профиль">--}}
{{--                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>--}}
{{--                                        </svg>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    @else--}}
{{--                        <div class="text-center py-8">--}}
{{--                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"--}}
{{--                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>--}}
{{--                            </svg>--}}
{{--                            <p class="text-gray-500 dark:text-gray-400 text-lg">--}}
{{--                                Нет других пользователей с этой книгой--}}
{{--                            </p>--}}
{{--                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">--}}
{{--                                Вы можете стать первым владельцем этой книги--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                </div>--}}

                <!-- Действия (только для владельца) -->
                @auth
                    @if(auth()->id() === $book->user_id || auth()->user()?->is_admin)
                        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Управление книгой</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <a href="{{ route('books.edit', $book) }}"
                                   class="flex items-center justify-center p-4 border border-gray-300 dark:border-gray-600
                                          rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-amber-100 dark:bg-amber-900
                                                    flex items-center justify-center mr-3 group-hover:bg-amber-200
                                                    dark:group-hover:bg-amber-800 transition-colors">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">Редактировать книгу</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Изменить информацию о книге</div>
                                        </div>
                                    </div>
                                </a>

                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="contents"
                                      onsubmit="return confirm('Вы уверены, что хотите удалить книгу "{{ addslashes($book->title) }}"?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex items-center justify-center p-4 border border-gray-300 dark:border-gray-600
                                                   rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group cursor-pointer
                                                   border-red-200 dark:border-red-800 w-full">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900
                                                        flex items-center justify-center mr-3 group-hover:bg-red-200
                                                        dark:group-hover:bg-red-800 transition-colors">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-red-700 dark:text-red-400">Удалить книгу</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Удалить книгу из системы</div>
                                        </div>
                                    </div>
                                </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .prose p {
            line-height: 1.7;
        }

        .hover\:bg-red-50:hover {
            background-color: rgba(254, 242, 242, var(--tw-bg-opacity));
        }
    </style>
@endpush
