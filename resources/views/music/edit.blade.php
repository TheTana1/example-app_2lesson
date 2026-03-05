@extends('layouts.main')

@section('title', 'Редактировать трек • ' . $track->title)

@section('content')

    <div class="container mx-auto px-4 py-8 max-w-3xl">

        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold">Редактировать трек</h1>
            <a href="{{ route('music.show', $track->id) }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Назад к треку
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

            <form action="{{ route('music.update', $track->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Название трека -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Название трека <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $track->title) }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           required
                           maxlength="255">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Артисты -->
                <div class="mb-8">
                    <label for="artists" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Артист(ы) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="artists_string"
                           id="artists"
                           value="{{ old('artists_string', implode(', ', $track->artists ?? [])) }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="Например: Artist One, Artist Two"
                           required>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        Вводите имена через запятую. Будет автоматически преобразовано в массив.
                    </p>
                    @error('artists_string')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Медиа-файлы с возможностью замены -->
                <div class="space-y-8 mb-10">

                    <!-- Обложка (с возможностью замены) -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            Обложка трека
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Текущая обложка -->
                            <div class="md:col-span-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Текущая обложка:</p>
                                <div class="relative aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                                    @if ($track->cover_path)
                                        <img src="{{ asset($track->cover_path) }}" alt="Обложка" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-6xl opacity-40">♪</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Загрузка новой обложки -->
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Загрузить новую обложку:</p>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-indigo-500 transition">
                                    <input type="file"
                                           name="cover_path"
                                           id="cover_path"
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewCover(this)">

                                    <label for="cover_path" class="cursor-pointer">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-indigo-600 dark:text-indigo-400 font-medium">Нажмите для выбора</span>
                                        <span class="text-gray-500 dark:text-gray-400 block text-sm mt-1">или перетащите файл сюда</span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500 block mt-2">JPG, PNG, GIF, WEBP (макс. 2 МБ)</span>
                                    </label>

                                    <!-- Превью новой обложки -->
                                    <div id="cover_preview_container" class="mt-4 hidden">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Новая обложка:</p>
                                        <img id="cover_preview" src="#" alt="Превью" class="max-h-32 mx-auto rounded-lg border border-gray-300 dark:border-gray-600">
                                    </div>
                                </div>
                                @error('cover_path')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    * Оставьте пустым, чтобы оставить текущую обложку
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Аудио файл (с возможностью замены) -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            Аудиофайл
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Текущий аудиофайл -->
                            <div class="md:col-span-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Текущий файл:</p>
                                <div class="bg-gray-100 dark:bg-gray-900/50 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                        </svg>
                                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">
                                            {{ basename($track->file_path) }}
                                        </span>
                                    </div>
                                    <audio controls class="w-full mt-1">
                                        <source src="{{ asset($track->file_path) }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            </div>

                            <!-- Загрузка нового аудиофайла -->
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Загрузить новый файл:</p>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-indigo-500 transition">
                                    <input type="file"
                                           name="file_path"
                                           id="file_path"
                                           accept="audio/mpeg,audio/mp3,audio/wav,audio/ogg"
                                           class="hidden"
                                           onchange="updateAudioName(this)">

                                    <label for="file_path" class="cursor-pointer">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414m2.828-9.9a9 9 0 012.828-2.828" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h8m-4-4v8" />
                                        </svg>
                                        <span class="text-indigo-600 dark:text-indigo-400 font-medium">Нажмите для выбора</span>
                                        <span class="text-gray-500 dark:text-gray-400 block text-sm mt-1">или перетащите файл сюда</span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500 block mt-2">MP3, WAV, OGG (макс. 10 МБ)</span>
                                    </label>

                                    <!-- Информация о новом файле -->
                                    <div id="audio_info" class="mt-4 hidden">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Новый файл:</p>
                                        <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-lg p-3">
                                            <p id="audio_filename" class="text-sm text-gray-700 dark:text-gray-300 font-medium"></p>
                                            <p id="audio_filesize" class="text-xs text-gray-500 dark:text-gray-400 mt-1"></p>
                                        </div>
                                    </div>
                                </div>
                                @error('file_path')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    * Оставьте пустым, чтобы оставить текущий аудиофайл
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Остальные поля (только просмотр) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Длительность
                            </label>
                            <input type="text" value="{{ gmdate('i:s', $track->duration) }}" disabled
                                   class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Жанр
                            </label>
                            <input type="text" value="{{ $track->genre?->value ?? $track->genre }}" disabled
                                   class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Дата релиза
                            </label>
                            <input type="text" value="{{ $track->release_date }}" disabled
                                   class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Прослушиваний
                            </label>
                            <input type="text" value="{{ number_format($track->plays) }}" disabled
                                   class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Кнопки -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('music.show', $track->id) }}"
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition text-center">
                        Отмена
                    </a>

                    <button type="submit"
                            class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-md">
                        Сохранить изменения
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- JavaScript для предпросмотра -->
    <script>
        function previewCover(input) {
            const previewContainer = document.getElementById('cover_preview_container');
            const preview = document.getElementById('cover_preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        function updateAudioName(input) {
            const audioInfo = document.getElementById('audio_info');
            const filenameSpan = document.getElementById('audio_filename');
            const filesizeSpan = document.getElementById('audio_filesize');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                filenameSpan.textContent = file.name;

                // Форматирование размера файла
                const size = file.size;
                if (size < 1024) {
                    filesizeSpan.textContent = size + ' Б';
                } else if (size < 1024 * 1024) {
                    filesizeSpan.textContent = (size / 1024).toFixed(1) + ' КБ';
                } else {
                    filesizeSpan.textContent = (size / (1024 * 1024)).toFixed(1) + ' МБ';
                }

                audioInfo.classList.remove('hidden');
            } else {
                audioInfo.classList.add('hidden');
            }
        }
    </script>

@endsection
