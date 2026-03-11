@extends('layouts.main')

@section('title', $track->title . ' — ' . implode(', ', $track->artists))

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">

        <!-- Header с навигацией -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                        <a href="{{ route('music.index') }}"
                           class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            Музыка
                        </a>
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $track->title }}</span>
                    </nav>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $track->title }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        {{ implode(', ', $track->artists) }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Кнопка редактирования -->
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('music.edit', $track->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600
                                  text-white font-medium rounded-lg transition-colors shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Редактировать
                            </a>


                            <!-- Кнопка удаления -->
                            <form action="{{ route('music.destroy', $track->id) }}" method="POST"
                                  onsubmit="return confirm('Вы уверены, что хотите удалить этот трек?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600
                                           text-white font-medium rounded-lg transition-colors shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Удалить
                                </button>
                            </form>
                        @endif
                    @endauth
                    <a href="{{ route('music.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700
                                  hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200
                                  font-medium rounded-lg transition-colors">
                        ← Назад
                    </a>
                </div>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if(session()->has('success'))
                <div class="m-5 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800
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
            @elseif(session()->has('error'))
                <div class="m-5 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800
                        rounded-lg text-red-700 dark:text-red-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            <!-- Левая колонка: обложка и информация -->
            <div class="md:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-8">
                    <!-- Обложка -->
                    <div class="aspect-square relative">
                        @if ($track->cover_path)
                            <img src="{{ asset($track->cover_path) }}"
                                 alt="{{ $track->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600
                                            flex items-center justify-center">
                                <span class="text-white text-8xl opacity-40">♪</span>
                            </div>
                        @endif

                        <!-- Длительность в углу -->
                        <div
                            class="absolute bottom-4 right-4 bg-black/70 text-white text-sm px-3 py-1.5 rounded-full backdrop-blur-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ gmdate('i:s', $track->duration) }}
                        </div>
                    </div>

                    <!-- Статистика -->
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Прослушиваний
                                </span>
                            <span class="font-bold text-indigo-600 dark:text-indigo-400 text-lg">
                                    {{ number_format($track->plays) }}
                                </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Дата релиза
                                </span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($track->release_date)->format('d.m.Y') }}
                                </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Жанр
                                </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                             bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    {{ $track->genre ?? 'Неизвестный жанр' }}
                                </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    Статус
                                </span>
                            <span class="inline-flex items-center">
                                    @if($track->is_published)
                                    <span class="flex items-center text-green-600 dark:text-green-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Опубликован
                                        </span>
                                @else
                                    <span class="flex items-center text-gray-500 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Черновик
                                        </span>
                                @endif
                                </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Правая колонка: плеер и детали -->
            <div class="md:col-span-2 space-y-6">
                <!-- Карточка плеера -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                            </svg>
                            Аудиоплеер
                        </h2>

                        <div class="space-y-4">
                            <!-- Информация о треке в плеере -->
                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $track->title }}</div>
                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-400">{{ implode(', ', $track->artists) }}</div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ gmdate('i:s', $track->duration) }}
                                </div>
                            </div>

                            <!-- Аудиоплеер -->
                            <audio controls class="w-full" data-track-id="{{ $track->id }}">
                                <source src="{{ asset($track->file_path) }}" type="audio/mpeg">
                                Ваш браузер не поддерживает аудио.
                            </audio>

                            <!-- Кнопки действий -->
                            <div class="flex items-center justify-between pt-2">
                                <div class="flex items-center gap-3">
                                    <!-- Кнопка избранного -->
                                    <form action="{{ route('music.save.favorite', $track->id) }}" method="POST">
                                        @csrf
                                        @if (in_array($track->id, auth()->user()?->musics->pluck('id')->toArray() ?? []))
                                            <button type="submit"
                                                    title="Убрать из избранного"
                                                    class="p-3 bg-white dark:bg-gray-700 rounded-full shadow-md hover:shadow-lg
                                                               hover:bg-red-50 dark:hover:bg-gray-600 transition-all
                                                               transform hover:scale-110 active:scale-95 group">
                                                <svg class="w-6 h-6 text-red-500 group-hover:text-red-600"
                                                     fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button type="submit"
                                                    title="Добавить в избранное"
                                                    class="p-3 bg-white dark:bg-gray-700 rounded-full shadow-md hover:shadow-lg
                                                               hover:bg-red-50 dark:hover:bg-gray-600 transition-all
                                                               transform hover:scale-110 active:scale-95 group">
                                                <svg class="w-6 h-6 text-gray-400 group-hover:text-red-400"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Кнопка скачивания -->
                                    <a href="{{ asset($track->file_path) }}"
                                       download
                                       class="p-3 bg-white dark:bg-gray-700 rounded-full shadow-md hover:shadow-lg
                                                  hover:bg-indigo-50 dark:hover:bg-gray-600 transition-all
                                                  transform hover:scale-110 active:scale-95 group">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>

                                <!-- Счетчик прослушиваний -->
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ number_format($track->plays) }} прослушиваний
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Карточка с дополнительной информацией -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Детальная информация
                        </h2>

                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">ID трека</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">#{{ $track->id }}</dd>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Дата добавления</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $track->created_at->format('d.m.Y H:i') }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Последнее обновление</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $track->updated_at->format('d.m.Y H:i') }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Формат файла</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ pathinfo($track->file_path, PATHINFO_EXTENSION) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========== НОВЫЙ БЛОК: Секция комментариев ========== -->
        <div class="mt-12">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Заголовок секции комментариев -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-7 h-7 mr-3 text-indigo-500" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Комментарии
                            <span class="ml-3 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                       px-3 py-1 rounded-full">
                                {{ $comments->total() }}
                            </span>
                        </h2>

                        <!-- Сортировка комментариев -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Сортировать:</span>
                            <select class="text-sm border-gray-300 dark:border-gray-600 rounded-lg
                                         bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                         focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="newest">Сначала новые</option>
                                <option value="oldest">Сначала старые</option>
                                <option value="popular">Популярные</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Форма добавления комментария (только для авторизованных) -->
                {{--                @auth--}}
                {{--                    <div class="p-6 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-200 dark:border-gray-700">--}}
                {{--                        <form action="{{ route('comments.store', ['type' => 'music', 'id' => $track->id]) }}"--}}
                {{--                              method="POST" class="space-y-4">--}}
                {{--                            @csrf--}}
                {{--                            <div>--}}
                {{--                                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">--}}
                {{--                                    Оставить комментарий--}}
                {{--                                </label>--}}
                {{--                                <textarea--}}
                {{--                                    name="comment"--}}
                {{--                                    id="comment"--}}
                {{--                                    rows="3"--}}
                {{--                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg--}}
                {{--                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-white--}}
                {{--                                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500--}}
                {{--                                           placeholder-gray-400 dark:placeholder-gray-500"--}}
                {{--                                    placeholder="Поделитесь своими мыслями о треке..."--}}
                {{--                                    required--}}
                {{--                                ></textarea>--}}
                {{--                            </div>--}}
                {{--                            <div class="flex justify-end">--}}
                {{--                                <button type="submit"--}}
                {{--                                        class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700--}}
                {{--                                               text-white font-medium rounded-lg transition-colors shadow-md--}}
                {{--                                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">--}}
                {{--                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
                {{--                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
                {{--                                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>--}}
                {{--                                    </svg>--}}
                {{--                                    Отправить комментарий--}}
                {{--                                </button>--}}
                {{--                            </div>--}}
                {{--                        </form>--}}
                {{--                    </div>--}}
                {{--                @else--}}
                {{--                    <div class="p-6 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-200 dark:border-gray-700 text-center">--}}
                {{--                        <p class="text-gray-600 dark:text-gray-400">--}}
                {{--                            Чтобы оставить комментарий,--}}
                {{--                            <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">--}}
                {{--                                войдите в систему--}}
                {{--                            </a>--}}
                {{--                        </p>--}}
                {{--                    </div>--}}
                {{--                @endauth--}}

                <!-- Список комментариев -->
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($comments as $comment)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                             id="comment-{{ $comment->id }}">
                            <div class="flex space-x-4">
                                <!-- Аватар пользователя -->
                                <div class="flex-shrink-0">
                                    @if($comment->user && $comment->user->avatar)
                                        <img src="{{ asset($comment->user->avatar) }}"
                                             alt="{{ $comment->user->name }}"
                                             class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-800">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                                    flex items-center justify-center text-white font-bold text-lg
                                                    border-2 border-indigo-200 dark:border-indigo-800">
                                            {{ mb_strtoupper(mb_substr($comment->user->name ?? 'Г', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                F

                                <!-- Контент комментария -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $comment->user->name ?? 'Пользователь' }}
                                            </h4>
                                            @if($comment->user && $comment->user->is_admin)
                                                <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/50
                                                           text-indigo-700 dark:text-indigo-300 text-xs rounded-full">
                                                    Администратор
                                                </span>
                                            @endif
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <!-- Кнопки действий для автора комментария или админа -->
                                        @auth
                                            @if(Auth::id() === $comment->user_id || Auth::user()->is_admin)
                                                <div class="flex items-center space-x-2">
                                                    <button onclick="editComment({{ $comment->id }})"
                                                            class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400
                                                                   transition-colors"
                                                            title="Редактировать">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <form action="{{ route('comments.destroy', $comment->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Удалить комментарий?')"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400
                                                                       transition-colors"
                                                                title="Удалить">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                 viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>

                                    <!-- Текст комментария -->
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300 mb-3">
                                        {{ $comment->comment }}
                                    </div>

                                    <!-- Кнопки взаимодействия -->
                                    <div class="flex items-center space-x-4 mt-2">
                                        <!-- Лайк -->
                                        <button onclick="likeComment({{ $comment->id }})"
                                                class="flex items-center space-x-1 text-gray-500 hover:text-red-500
                                                       dark:text-gray-400 dark:hover:text-red-400 transition-colors group">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            <span class="text-sm">{{ $comment->likes_count ?? 0 }}</span>
                                        </button>

                                        <!-- Ответить -->
                                        <button
                                            onclick="replyToComment({{ $comment->id }}, '{{ $comment->user->name ?? 'Пользователь' }}')"
                                            class="flex items-center space-x-1 text-gray-500 hover:text-indigo-600
                                                       dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                            </svg>
                                            <span class="text-sm">Ответить</span>
                                        </button>
                                    </div>

                                    <!-- Вложенные ответы (если есть) -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div
                                            class="mt-4 ml-6 pl-4 border-l-2 border-indigo-200 dark:border-indigo-800 space-y-4">
                                            @foreach($comment->replies as $reply)
                                                <!-- Аналогичная структура для ответов -->
                                                <div class="flex space-x-3">
                                                    <div class="flex-shrink-0">
                                                        @if($reply->user && $reply->user->avatar)
                                                            <img src="{{ asset($reply->user->avatar) }}"
                                                                 alt="{{ $reply->user->name }}"
                                                                 class="w-8 h-8 rounded-full object-cover">
                                                        @else
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500
                                                                        flex items-center justify-center text-white font-bold text-sm">
                                                                {{ strtoupper(substr($reply->user->name ?? 'Г', 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <span
                                                                class="font-medium text-gray-900 dark:text-white text-sm">
                                                                {{ $reply->user->name ?? 'Пользователь' }}
                                                            </span>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $reply->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            {{ $reply->comment }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20
                                        bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                Нет комментариев
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                Будьте первым, кто оставит комментарий к этому треку!
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Пагинация комментариев -->
                @if($comments instanceof \Illuminate\Pagination\LengthAwarePaginator && $comments->hasPages())
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                        {{ $comments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Скрипты для комментариев -->
    <script>
        function editComment(commentId) {
            // Реализация редактирования комментария
            alert('Редактирование комментария ' + commentId);
        }

        function likeComment(commentId) {
            // Реализация лайка комментария
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Обновление счетчика лайков
                    if (data.success) {
                        // Обновляем UI
                    }
                });
        }

        function replyToComment(commentId, username) {
            const replyForm = document.createElement('div');
            replyForm.innerHTML = `
                <div class="mt-4 ml-12">
                    <form action="/comments/${commentId}/reply" method="POST" class="space-y-2">
                        @csrf
            <textarea name="comment" rows="2"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                             focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="Ответить ${username}..."></textarea>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="this.closest('div').remove()"
                                    class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="px-3 py-1 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Ответить
                            </button>
                        </div>
                    </form>
                </div>
            `;

            const commentElement = document.getElementById(`comment-${commentId}`);
            commentElement.appendChild(replyForm.firstChild);
        }
    </script>
    <!-- Скрипт для отслеживания прослушиваний -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const audio = document.querySelector('audio[data-track-id]');
            if (!audio) return;

            const trackId = audio.dataset.trackId;
            let played = false;

            audio.addEventListener('timeupdate', function () {
                if (played) return;

                const duration = this.duration;
                const currentTime = this.currentTime;

                if (!duration || duration <= 0 || !isFinite(duration)) return;

                // Отправляем запрос при достижении 70%
                const seventyPercent = duration * 0.7;

                if (currentTime >= seventyPercent) {
                    played = true;

                    fetch("{{ route('music.track.listen_progress') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            track_id: trackId,
                            played_percent: 70,
                            duration: Math.round(duration)
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.plays) {
                                // Обновляем счетчик прослушиваний на странице
                                const playsElements = document.querySelectorAll('.plays-count');
                                playsElements.forEach(el => {
                                    el.textContent = data.plays.toLocaleString();
                                });
                            }
                        })
                        .catch(error => console.error('Ошибка:', error));
                }
            });
        });
    </script>
@endsection
