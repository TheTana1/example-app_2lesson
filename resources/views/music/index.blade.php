@extends('layouts.main')

@section('title', 'Музыка')

@section('content')

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-8">{{$pageTitle}}</h1>
            </div>

            @auth
                <a href="{{ route('music.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700
                  text-white font-medium rounded-lg shadow-md transition-all duration-200
                  transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Добавить песню
                </a>
            @endauth
        </div>



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
                                    @if (in_array($track->id, auth()->user()?->musics->pluck('id')->toArray() ?? []))
                                        <button type="submit"
                                                title="Убрать из избранного"
                                                class="p-2.5 bg-white/80 dark:bg-gray-900/80 rounded-full shadow-md hover:bg-white dark:hover:bg-gray-800 transition-all transform hover:scale-110 active:scale-95">
                                            <svg class="w-7 h-7 text-red-500"
                                                 fill="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path
                                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button type="submit"
                                                title="Добавить в избранное"
                                                class="p-2.5 bg-white/80 dark:bg-gray-900/80 rounded-full shadow-md hover:bg-white dark:hover:bg-gray-800 transition-all transform hover:scale-110 active:scale-95">
                                            <svg class="w-7 h-7 text-gray-400 hover:text-red-400"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </form>
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
