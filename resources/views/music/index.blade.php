@extends('layouts.main')

@section('title', 'Музыка')

@section('content')

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{$pageTitle}}</h1>
            </div>
            @auth
                @if($user->isAdmin())
                    <a href="{{ route('music.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700
                  text-white font-medium rounded-lg shadow-md transition-all duration-200
                  transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Добавить песню
                    </a>
                @endif
            @endauth
        </div>

        <!-- Форма фильтрации -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Фильтры</h2>

            <form action="{{ route('music.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Поиск по названию -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Название трека
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ request('title') }}"
                           placeholder="Введите название..."
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                <!-- Поиск по исполнителю -->
                <div>
                    <label for="artist" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Исполнитель
                    </label>
                    <input type="text"
                           name="artist"
                           id="artist"
                           value="{{ request('artist') }}"
                           placeholder="Введите имя исполнителя..."
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
{{--                @dd($genres[0])--}}
                <!-- Фильтр по жанру -->
                <div>
                    <label for="genre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Жанр
                    </label>
                    <select name="genre"
                            id="genre"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="">Все жанры</option>

                        @foreach($genres as $genre)
                            <option value="{{ $genre['value'] }}" {{ request('genre') == $genre['value'] ? 'selected' : '' }}>
                                {{ $genre['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Кнопки действий -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Найти
                    </button>

                    <a href="{{ route('music.index') }}"
                       class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg shadow-md transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            </form>

            <!-- Отображение активных фильтров -->
            @if(request()->anyFilled(['title', 'artist', 'genre']))
                <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Активные фильтры:</span>

                    @if(request('title'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400">
                            Название: "{{ request('title') }}"
                        </span>
                    @endif

                    @if(request('artist'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400">
                            Исполнитель: "{{ request('artist') }}"
                        </span>
                    @endif

                    @if(request('genre'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400">
                            Жанр: {{ $genres[request('genre')] ?? request('genre') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

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


        @if ($tracks->isEmpty())
            <p class="text-gray-500 text-center py-12">Пока нет опубликованных треков...</p>
        @else

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach ($tracks as $track)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">

                        <!-- Обложка -->
                        <div class="relative aspect-square">
                            @if ($track->cover_path)
                                <img src="{{ asset($track->cover_path) }}"
                                     alt="{{ $track->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white text-5xl opacity-40">♪</span>
                                </div>
                            @endif

                            <!-- Длительность в углу -->
                            <div class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded">
                                {{ gmdate('i:s', $track->duration) }}
                            </div>
                        </div>

                        <!-- Информация -->
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1 line-clamp-1">
                                {{ $track->title }}
                            </h3>

                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-1">
                                {{ implode(', ', $track->artists) }}
                            </p>

                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Прослушиваний: {{ number_format($track->plays) }}</span>
                                <span>{{ $track->release_date }}</span>
                            </div>

                            <div class="mt-3">
                                <audio controls class="w-full h-10" data-track-id="{{ $track->id }}">
                                    <source src="{{ asset($track->file_path) }}" type="audio/mpeg">
                                    Ваш браузер не поддерживает аудио.
                                </audio>
                            </div>

                            <!-- Кнопка "Добавить в избранное / плейлист" -->
                            <div class="mt-3 flex justify-end">
                                <form action="{{ route('music.save.favorite', $track->id) }}" method="POST">
                                    @csrf
                                    @if (in_array($track->id, $favoriteIds ?? []))
                                        <button type="submit"
                                                title="Убрать из избранного"
                                                class="p-2.5 bg-white/80 dark:bg-gray-900/80 rounded-full shadow-md hover:bg-white dark:hover:bg-gray-800 transition-all transform hover:scale-110 active:scale-95">
                                            <svg class="w-7 h-7 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button type="submit"
                                                title="Добавить в избранное"
                                                class="p-2.5 bg-white/80 dark:bg-gray-900/80 rounded-full shadow-md hover:bg-white dark:hover:bg-gray-800 transition-all transform hover:scale-110 active:scale-95">
                                            <svg class="w-7 h-7 text-gray-400 hover:text-red-400"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </form>


                                <a href="{{ route('music.show', $track->id) }}">
                                    <!-- Просмотреть (глаз) -->
                                    <button type="button"
                                            title="Просмотреть"
                                            class="p-2.5 bg-white/80 dark:bg-gray-900/80 rounded-full shadow-md hover:bg-white dark:hover:bg-gray-800 transition-all transform hover:scale-110 active:scale-95">
                                        <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </a>

                                @if($user->isAdmin())
                                    <form action="{{ route('music.destroy', $track->id) }}" method="POST"
                                          onsubmit="return confirm('Удалить трек «{{ $track->title }}»?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Удалить трек"
                                                class="p-2.5 bg-white/80 dark:bg-gray-900/80 rounded-full shadow-md hover:bg-white dark:hover:bg-gray-800 transition-all transform hover:scale-110 active:scale-95">
                                            <svg class="w-7 h-7 text-red-600 dark:text-red-400"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>

                    </div>

                @endforeach

            </div>

            <!-- Пагинация -->
            <div class="mt-10">
                {{ $tracks->links() }}
            </div>

        @endif

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Объект для отслеживания отправленных запросов по каждому треку
            const playedTracks = {};

            // Находим все аудио элементы на странице
            const audioElements = document.querySelectorAll('audio');

            audioElements.forEach(audio => {
                // Получаем ID трека из родительского элемента или data-атрибута
                // Вам нужно будет добавить data-track-id к аудио элементу или найти его другим способом
                let trackId = audio.dataset.trackId;
                if (!trackId) return;

                // Инициализируем отслеживание для этого трека
                playedTracks[trackId] = false;

                audio.addEventListener('timeupdate', function () {
                    // Проверяем, не отправляли ли уже запрос для этого трека
                    if (playedTracks[trackId]) return;

                    const duration = this.duration;
                    const currentTime = this.currentTime;

                    // Проверяем, что длительность известна (не NaN) и больше 0
                    if (!duration || duration <= 0) return;

                    // Вычисляем 70% от длительности трека
                    const seventyPercent = duration * 0.02 //0.7;

                    // Если достигли 70% и запрос еще не отправлен
                    if (currentTime >= seventyPercent) {
                        // Отмечаем что запрос отправлен
                        playedTracks[trackId] = true;
                        console.log(213);
                        // Отправляем запрос на сервер
                        fetch("{{route('music.track.listen_progress')}}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''

                            },
                            body: JSON.stringify({
                                track_id: trackId,
                                played_percent: 0.02,  //0.7,
                                duration: duration
                            })
                        })
                            .then(response => {
                                if (!response.ok) {
                                    console.error('Ошибка при отправке статистики прослушивания');
                                }
                            })
                            .catch(error => {
                                console.error('Ошибка сети:', error);
                            });
                    }
                });


            });
        });
    </script>
@endsection
