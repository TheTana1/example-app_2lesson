@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Редактировать книгу
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Редактирование информации о книге
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('books.show', $book) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700
                          hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200
                          font-medium rounded-lg transition-colors">
                        ← К книге
                    </a>

                    <a href="{{ route('books.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700
                          hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200
                          font-medium rounded-lg transition-colors">
                        ← Назад к списку
                    </a>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="p-6 sm:p-8">

                <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        <!-- Название книги -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Название книги <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       name="title"
                                       id="title"
                                       value="{{ old('title', $book->title) }}"
                                       required
                                       placeholder="Введите название книги..."
                                       class="block w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">

                                @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Slug пользователя -->
                        <div>
                            <label for="user_slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Slug владельца <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       name="user_slug"
                                       id="user_slug"
                                       value="{{ old('user_slug', $book->user->slug ?? '') }}"
                                       required
                                       placeholder="Введите slug пользователя (например: john-doe)"
                                       class="block w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('user_slug') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">

                                @error('user_slug')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Текущий владелец: <strong>{{ $book->user->name ?? 'Не указан' }}</strong> ({{ $book->user->slug ?? '' }})
                            </p>
                        </div>

                        <!-- Дополнительная информация -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                Дополнительная информация <span class="text-sm text-gray-500 dark:text-gray-400">(необязательно)</span>
                            </h3>

                            <!-- Автор -->
                            <div class="mb-4">
                                <label for="author"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Автор
                                </label>
                                <input type="text"
                                       name="author"
                                       id="author"
                                       value="{{ old('author', $book->author) }}"
                                       placeholder="Например: Лев Толстой"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('author') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                @error('author')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Год издания -->
                            <div>
                                <label for="published_year"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Год издания
                                </label>
                                <input type="number"
                                       name="published_year"
                                       id="published_year"
                                       value="{{ old('published_year', $book->published_year) }}"
                                       placeholder="2024"
                                       min="1800"
                                       max="2099"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('published_year') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                @error('published_year')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Информация о создании (только для чтения) -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Информация о записи</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Дата создания:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white font-medium">
                                        {{ $book->created_at ? $book->created_at->format('d.m.Y H:i') : '—' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Последнее обновление:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white font-medium">
                                        {{ $book->updated_at ? $book->updated_at->format('d.m.Y H:i') : '—' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('books.show', $book) }}"
                               class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                                  text-gray-800 dark:text-gray-200 font-medium rounded-lg transition">
                                Отмена
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700
                                       text-white font-medium rounded-lg shadow-md transition-all duration-200
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Сохранить изменения
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
