@extends('layouts.main')

@section('title', 'Music Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-purple-900/20 dark:to-gray-900">

        <!-- Декоративные элементы -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 dark:bg-purple-700 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300 dark:bg-indigo-700 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-40 w-80 h-80 bg-pink-300 dark:bg-pink-700 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="container mx-auto px-4 py-12 relative">
            <!-- Приветственная секция -->
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <div class="flex items-center justify-center space-x-2">
                        <div class="w-2 h-2 bg-indigo-600 dark:bg-indigo-400 rounded-full animate-pulse"></div>
                        <div class="w-2 h-2 bg-purple-600 dark:bg-purple-400 rounded-full animate-pulse animation-delay-200"></div>
                        <div class="w-2 h-2 bg-pink-600 dark:bg-pink-400 rounded-full animate-pulse animation-delay-400"></div>
                    </div>
                </div>

                <h1 class="text-5xl p-2 md:text-6xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400">
                    Добро пожаловать, {{ Auth::user()->name ?? 'Meloman' }}! 🎵
                </h1>

                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Погрузись в мир музыки, открывай новых исполнителей и создавай свою идеальную коллекцию
                </p>
            </div>

            <!-- Быстрые действия -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                <a href="{{ route('music.index') }}"
                   class="group relative px-8 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center space-x-3">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        <span class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-white transition-colors">Слушать музыку</span>
                    </div>
                </a>

                <a href="{{ route('favorites.index') }}"
                   class="group relative px-8 py-4 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-red-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center space-x-3">
                        <svg class="w-6 h-6 text-pink-600 dark:text-pink-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-white transition-colors">Мои треки</span>
                    </div>
                </a>
            </div>

            <!-- Секции с музыкой -->
            <div class="space-y-12">
                <!-- Недавно прослушанное -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Недавно прослушано
                        </h2>
                        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">Смотреть все →</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @forelse($recentlyPlayed ?? [] as $track)
                            <div class="group cursor-pointer">
                                <div class="relative aspect-square rounded-xl overflow-hidden mb-2 shadow-lg group-hover:shadow-2xl transition-all duration-300">
                                    @if($track->cover_path)
                                        <img src="{{ asset($track->cover_path) }}" alt="{{ $track->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-4xl opacity-40">♪</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <h3 class="font-semibold text-gray-800 dark:text-white text-sm truncate">{{ $track->title }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ implode(', ', $track->artists ?? []) }}</p>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500 dark:text-gray-400">История прослушиваний пуста</p>
                                <a href="{{ route('music.index') }}" class="inline-block mt-2 text-indigo-600 dark:text-indigo-400 hover:underline">Начать слушать →</a>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Рекомендации -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Рекомендации для вас
                        </h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @forelse($recommendations ?? [] as $track)
                            <div class="group cursor-pointer">
                                <div class="relative aspect-square rounded-xl overflow-hidden mb-2 shadow-lg group-hover:shadow-2xl transition-all duration-300">
                                    @if($track->cover_path)
                                        <img src="{{ asset($track->cover_path) }}" alt="{{ $track->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                            <span class="text-white text-4xl opacity-40">♪</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <h3 class="font-semibold text-gray-800 dark:text-white text-sm truncate">{{ $track->title }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ implode(', ', $track->artists ?? []) }}</p>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500 dark:text-gray-400">Скоро здесь появятся рекомендации</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Популярные плейлисты -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Популярные плейлисты
                        </h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($playlists ?? [] as $playlist)
                            <div class="group cursor-pointer">
                                <div class="relative aspect-[4/3] rounded-xl overflow-hidden mb-2 shadow-lg group-hover:shadow-2xl transition-all duration-300">
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600"></div>
                                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center">
                                        <span class="text-white font-bold text-xl">{{ $playlist->name }}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $playlist->tracks_count ?? 0 }} треков</p>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500 dark:text-gray-400">Плейлисты пока не созданы</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Статистика дня -->
            <div class="mt-16 bg-white/50 dark:bg-gray-800/50 backdrop-blur-lg rounded-2xl p-8 border border-white/20 dark:border-gray-700/50">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $todayPlays ?? 0 }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Прослушано сегодня</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $totalTracks ?? 0 }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Всего треков</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-pink-600 dark:text-pink-400">{{ $favoriteTracks ?? 0 }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">В избранном</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-rose-600 dark:text-rose-400">{{ $artistsCount ?? 0 }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Любимых артистов</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
@endsection
