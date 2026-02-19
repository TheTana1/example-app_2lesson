@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Профиль пользователя
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Просмотр и управление профилем
                    </p>
                </div>
                @auth
                <div class="flex items-center space-x-3">
                    <a href="{{ route('users.edit', $user) }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700
                              text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Редактировать
                    </a>
                    @endauth
                    <a href="{{ url()->previous() }}"
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

        <!-- Основная карточка -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Левая колонка - Аватар и основная информация -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">

                    <!-- Аватар -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-4">

                            @if($user->avatar && Storage::disk('public')->exists('/avatars/'.basename($user->avatar->path)))

                                <div class="h-48 w-48 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-xl">
                                    <img src="{{ asset($user->avatar->path) }}"
{{--                                    <img src="{{ Storage::disk('public')->url('/avatars/'.basename($user->avatar->path)) }}"--}}
                                         alt="{{ $user->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <!-- Дефолтный аватар с первой буквой имени -->
                                <div class="h-48 w-48 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                            flex items-center justify-center text-white font-bold text-5xl shadow-xl
                                            border-4 border-white dark:border-gray-700">
                                    {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <!-- Статус онлайн/офлайн -->
                            <div class="absolute bottom-4 right-4">
                                <div class="h-6 w-6 rounded-full bg-green-500 border-2 border-white dark:border-gray-800
                                            {{ $user->active  ? 'bg-green-500' : 'bg-gray-400' }}"
                                     title="{{ $user->active  ? 'Online' : 'Offline' }}">
                                </div>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $user->email }}</p>

                        <!-- Роль пользователя -->
{{--                        @if($user->role)--}}
{{--                            <div class="mt-3">--}}
{{--                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium--}}
{{--                                           {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' :--}}
{{--                                              $user->role === 'manager' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' :--}}
{{--                                              'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">--}}
{{--                                    {{ ucfirst($user->role) }}--}}
{{--                                </span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>

                    <!-- Статистика -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Статистика</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $user->posts_count ?? 0 }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Постов</div>
                            </div>

                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ $user->comments_count ?? 0 }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Комментариев</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Информация о создании -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Информация</h3>

                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">Зарегистрирован:</span>
                            <span class="ml-auto text-gray-900 dark:text-white">
                                {{ $user->created_at->format('d.m.Y') }}
                            </span>
                        </div>

                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Последнее обновление:</span>
                            <span class="ml-auto text-gray-900 dark:text-white">
                                {{ $user->updated_at->format('d.m.Y H:i') }}
                            </span>
                        </div>

                        @if($user->email_verified_at)
                            <div class="flex items-center text-green-600 dark:text-green-400">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Email подтвержден</span>
                            </div>
                        @else
                            <div class="flex items-center text-amber-600 dark:text-amber-400">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.092 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span class="font-medium">Email не подтвержден</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Правая колонка - Подробная информация -->
            <div class="lg:col-span-2">
                <!-- Контактная информация -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Контактная информация</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Полное имя
                                </label>
                                <div class="text-gray-900 dark:text-white font-medium">
                                    {{ $user->name }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Email адрес
                                </label>
                                <div class="flex items-center">
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Подтвержден
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Устройства
                                </label>
                                <div class="text-gray-900 dark:text-white font-medium">
                                    @if($user->phones->isNotEmpty())
                                        @foreach($user->phones as $phone)
                                            <p>{{ optional($phone->phoneBrand)->name ?? 'Без бренда' }}: {{ $phone->number }}</p>
                                        @endforeach
                                    @else
                                        <p class="text-gray-500">Нет телефонов</p>
                                    @endif
                                </div>
                            </div>


                        <div class="space-y-4">
                            @if($user->position)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Должность
                                    </label>
                                    <div class="text-gray-900 dark:text-white font-medium">
                                        {{ $user->position }}
                                    </div>
                                </div>
                            @endif

                            @if($user->department)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Отдел
                                    </label>
                                    <div class="text-gray-900 dark:text-white font-medium">
                                        {{ $user->department }}
                                    </div>
                                </div>
                            @endif

                            @if($user->location)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Местоположение
                                    </label>
                                    <div class="text-gray-900 dark:text-white font-medium">
                                        {{ $user->location }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Активность -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Последняя активность</h3>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                                        <div class="flex items-center justify-between mb-6">
                                            <h3 class="text-lg font-bold text-gray-600 dark:text-white">
                                                Последние книги:
                                            </h3>
                                        </div>

                                        @if($user->books)
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                @foreach($user->books as $book)
                                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                        <div class="flex-shrink-0">

                                                            @if($book->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $book->avatar->path)))
                                                                <img src="{{ asset($book->avatar->path) }}"
                                                                     alt="{{ $book->title }}"
                                                                     class="h-10 w-10 rounded-full object-cover">
                                                            @else
                                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                                                            flex items-center justify-center text-white text-sm font-bold">
                                                                    {{ strtoupper(mb_substr($book->title, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-3 flex-1">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ $book->title }}
                                                            </div>
{{--                                                            <div class="text-xs text-gray-500 dark:text-gray-400">--}}
{{--                                                                {{ $book->email }}--}}
{{--                                                            </div>--}}
                                                        </div>
                                                        <a href="{{ route('books.show', $book) }}"
                                                           class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                                           title="Посмотреть книгу">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-8">
                                                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                <p class="text-gray-400 dark:text-gray-400 text-l">
                                                    Здесь пока что пусто
                                                </p>
                                            </div>
                                        @endif
                                    </div>

{{--                    <div class="space-y-4">--}}
{{--                        @if($user->last_login_at)--}}
{{--                            <div class="flex items-start">--}}
{{--                                <div class="flex-shrink-0 mt-1">--}}
{{--                                    <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">--}}
{{--                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>--}}
{{--                                        </svg>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="ml-4">--}}
{{--                                    <p class="text-gray-900 dark:text-white font-medium">Последний вход в систему</p>--}}
{{--                                    <p class="text-gray-600 dark:text-gray-400 text-sm">--}}
{{--                                        {{ $user->last_login_at->format('d.m.Y в H:i') }}--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if($user->last_seen)--}}
{{--                            <div class="flex items-start">--}}
{{--                                <div class="flex-shrink-0 mt-1">--}}
{{--                                    <div class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">--}}
{{--                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>--}}
{{--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>--}}
{{--                                        </svg>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="ml-4">--}}
{{--                                    <p class="text-gray-900 dark:text-white font-medium">Был онлайн</p>--}}
{{--                                    <p class="text-gray-600 dark:text-gray-400 text-sm">--}}
{{--                                        {{ $user->last_seen->diffForHumans() }}--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
                </div>

                <!-- Действия -->
                @auth
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Действия</h3>


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('users.edit', $user) }}"
                           class="flex items-center justify-center p-4 border border-gray-300 dark:border-gray-600
                                  rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900
                                            flex items-center justify-center mr-3 group-hover:bg-blue-200
                                            dark:group-hover:bg-blue-800 transition-colors">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Редактировать профиль</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Изменить данные пользователя</div>
                                </div>
                            </div>
                        </a>

                        @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user->slug) }}" method="POST" class="contents"
                                  onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex items-center justify-center p-4 border border-gray-300 dark:border-gray-600
                                           rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group cursor-pointer
                                           border-red-200 dark:border-red-800">
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
                                            <div class="font-medium text-red-700 dark:text-red-400">Удалить пользователя</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Удалить аккаунт из системы</div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        @else
                            <div class="flex items-center justify-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg opacity-50">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">Удалить пользователя</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Нельзя удалить свой аккаунт</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endauth
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .avatar-container {
            transition: all 0.3s ease;
        }

        .avatar-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
