@extends('layouts.main')

@section('title', 'Добавление музыки')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Новая композиция
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Добавление нового трека в библиотеку
                    </p>
                </div>

                <a href="{{ route('music.index') }}"
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

                <form method="POST" action="{{ route('music.store') }}" enctype="multipart/form-data" id="musicForm">
                    @csrf

                    <div class="space-y-6">

                        <!-- Обложка + название -->
                        <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                            <!-- Обложка трека -->
                            <div class="flex-shrink-0">
                                <div class="relative group">
                                    <div id="coverPreview"
                                         class="h-32 w-32 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600
                                                flex items-center justify-center text-white font-bold text-4xl shadow-md
                                                overflow-hidden cursor-pointer border-2 border-transparent
                                                group-hover:border-indigo-400 transition-all duration-200">
                                        <span id="coverIcon" class="z-10">♪</span>
                                        <img id="coverImage"
                                             src=""
                                             alt="Cover preview"
                                             class="absolute inset-0 w-full h-full object-cover hidden">
                                    </div>

                                    <!-- Кнопка загрузки -->
                                    <div class="mt-3 flex flex-col items-center space-y-2">
                                        <label for="cover"
                                               class="cursor-pointer inline-flex items-center px-3 py-1.5
                                                      bg-indigo-100 dark:bg-indigo-900 hover:bg-indigo-200
                                                      dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300
                                                      text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Загрузить обложку
                                        </label>
                                    </div>

                                    <!-- Скрытый input для загрузки файла -->
                                    <input type="file"
                                           name="cover_path"
                                           id="cover"
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewCover(event)">

                                    <button type="button"
                                            id="removeCover"
                                            class="hidden text-xs text-gray-500 dark:text-gray-400
                                                   hover:text-red-500 dark:hover:text-red-400 transition mt-1">
                                        Удалить обложку
                                    </button>
                                </div>

                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">
                                    JPEG, PNG, не более 2MB
                                </div>

                                @error('cover_path')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400 text-center">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Название трека -->
                            <div class="flex-1">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Название трека <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="title"
                                       id="title"
                                       value="{{ old('title') }}"
                                       required
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                       placeholder="Введите название трека">

                                @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Исполнители -->
                        <div>
                            <label for="artists" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Исполнитель(и) <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="artists"
                                   id="artists"
                                   value="{{ old('artists') }}"
                                   required
                                   class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                      dark:bg-gray-700 dark:text-white shadow-sm
                                      focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                      @error('artists') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Например: The Beatles, Queen">

                            @error('artists')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Аудио файл и длительность -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="audio_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Аудио файл <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input type="file"
                                           name="file_path"
                                           id="audio_file"
                                           accept="audio/mpeg, audio/wav, audio/mp3"
                                           required
                                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                  file:mr-4 file:py-2.5 file:px-4
                                                  file:rounded-lg file:border-0
                                                  file:text-sm file:font-medium
                                                  file:bg-indigo-50 file:text-indigo-700
                                                  dark:file:bg-indigo-900 dark:file:text-indigo-300
                                                  hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800
                                                  file:cursor-pointer file:transition-colors
                                                  @error('file_path') border-red-300 @enderror"
                                           onchange="updateAudioDuration(this)">

                                    <div id="audioInfo" class="mt-2 hidden">
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="text-gray-600 dark:text-gray-400">Длительность:</span>
                                            <span id="durationDisplay" class="font-medium text-indigo-600 dark:text-indigo-400">0:00</span>
                                        </div>
                                    </div>
                                </div>

                                @error('file_path')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Скрытое поле для duration -->
                            <input type="hidden" name="duration" id="duration" value="{{ old('duration', 0) }}">

                            <!-- Жанр -->
                            <div>
                                <label for="genre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Жанр <span class="text-red-500">*</span>
                                </label>
                                <select name="genre"
                                        id="genre"
                                        required
                                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                           dark:bg-gray-700 dark:text-white shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                           @error('genre') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    <option value="">Выберите жанр</option>
                                    <option value="1" {{ old('genre') == 1 ? 'selected' : '' }}>Pop</option>
                                    <option value="2" {{ old('genre') == 2 ? 'selected' : '' }}>Rock</option>
                                    <option value="3" {{ old('genre') == 3 ? 'selected' : '' }}>Hip-Hop</option>
                                    <option value="4" {{ old('genre') == 4 ? 'selected' : '' }}>Electronic</option>
                                    <option value="5" {{ old('genre') == 5 ? 'selected' : '' }}>Jazz</option>
                                    <option value="6" {{ old('genre') == 6 ? 'selected' : '' }}>Classical</option>
                                    <option value="7" {{ old('genre') == 7 ? 'selected' : '' }}>R&B</option>
                                    <option value="8" {{ old('genre') == 8 ? 'selected' : '' }}>Country</option>
                                    <option value="9" {{ old('genre') == 9 ? 'selected' : '' }}>Metal</option>
                                    <option value="10" {{ old('genre') == 10 ? 'selected' : '' }}>Other</option>
                                </select>

                                @error('genre')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Дата релиза и статус публикации -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="release_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Дата релиза <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       name="release_date"
                                       id="release_date"
                                       value="{{ old('release_date', date('Y-m-d')) }}"
                                       required
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600
                                          dark:bg-gray-700 dark:text-white shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5
                                          @error('release_date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">

                                @error('release_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="is_published" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Статус публикации
                                </label>
                                <div class="flex items-center h-[42px]">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               name="is_published"
                                               id="is_published"
                                               value="1"
                                               {{ old('is_published', true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4
                                                    peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full
                                                    peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full
                                                    peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px]
                                                    after:start-[2px] after:bg-white after:border-gray-300 after:border
                                                    after:rounded-full after:h-5 after:w-5 after:transition-all
                                                    dark:border-gray-600 peer-checked:bg-indigo-600">
                                        </div>
                                        <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Опубликован
                                        </span>
                                    </label>
                                </div>

                                @error('is_published')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-8">
                            <a href="{{ route('music.index') }}"
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
                                          d="M12 4v16m8-8H4" />
                                </svg>
                                Добавить трек
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>

    <script>
        // Цвета для обложек
        const coverColors = [
            'from-blue-500 to-cyan-600',
            'from-green-500 to-emerald-600',
            'from-amber-500 to-yellow-600',
            'from-rose-500 to-pink-600',
            'from-violet-500 to-purple-600',
            'from-indigo-500 to-purple-600',
            'from-red-500 to-orange-600',
            'from-teal-500 to-green-600',
        ];

        // Получаем элементы DOM
        const coverPreview = document.getElementById('coverPreview');
        const coverImage = document.getElementById('coverImage');
        const coverIcon = document.getElementById('coverIcon');
        const coverInput = document.getElementById('cover');
        const removeCoverBtn = document.getElementById('removeCover');
        const titleInput = document.getElementById('title');

        // Обработчик клика по превью для выбора файла
        coverPreview.addEventListener('click', () => {
            coverInput.click();
        });

        // Функция для предпросмотра выбранной обложки
        function previewCover(event) {
            const file = event.target.files[0];
            if (file) {
                // Проверка типа файла
                if (!file.type.match('image.*')) {
                    alert('Пожалуйста, выберите файл изображения.');
                    return;
                }

                // Проверка размера файла (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Размер файла не должен превышать 2MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    coverImage.src = e.target.result;
                    coverImage.classList.remove('hidden');
                    coverIcon.classList.add('hidden');
                    coverPreview.classList.remove('bg-gradient-to-br');

                    // Показываем кнопку удаления
                    removeCoverBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Функция для удаления обложки
        removeCoverBtn.addEventListener('click', (e) => {
            e.stopPropagation();

            // Сбрасываем input файла
            coverInput.value = '';

            // Скрываем изображение
            coverImage.src = '';
            coverImage.classList.add('hidden');
            coverIcon.classList.remove('hidden');

            // Восстанавливаем градиент
            updateCoverColor();

            // Скрываем кнопку удаления
            removeCoverBtn.classList.add('hidden');
        });

        // Функция для обновления цвета обложки на основе названия
        function updateCoverColor() {
            const title = titleInput.value.trim();

            if (title.length > 0) {
                // Выбираем цвет на основе названия
                let hash = 0;
                for (let i = 0; i < title.length; i++) {
                    hash = title.charCodeAt(i) + ((hash << 5) - hash);
                }
                const colorIndex = Math.abs(hash) % coverColors.length;
                const selectedColor = coverColors[colorIndex];

                // Обновляем градиент
                coverPreview.className = coverPreview.className.replace(
                    /from-\w+-\d+ to-\w+-\d+/g,
                    selectedColor
                );

                // Добавляем градиент если его нет
                if (!coverPreview.className.includes('bg-gradient-to-br')) {
                    coverPreview.classList.add('bg-gradient-to-br', ...selectedColor.split(' '));
                }
            } else {
                coverPreview.className = coverPreview.className.replace(
                    /from-\w+-\d+ to-\w+-\d+/g,
                    'from-indigo-500 to-purple-600'
                );

                if (!coverPreview.className.includes('bg-gradient-to-br')) {
                    coverPreview.classList.add('bg-gradient-to-br', 'from-indigo-500', 'to-purple-600');
                }
            }
        }

        // Функция для определения длительности аудио
        function updateAudioDuration(input) {
            const file = input.files[0];
            if (file) {
                // Проверка типа файла
                const allowedTypes = ['audio/mpeg', 'audio/wav', 'audio/mp3'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Пожалуйста, выберите аудио файл (MP3, WAV).');
                    input.value = '';
                    return;
                }

                // Проверка размера файла (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Размер файла не должен превышать 10MB.');
                    input.value = '';
                    return;
                }

                const audio = new Audio();
                audio.src = URL.createObjectURL(file);

                audio.addEventListener('loadedmetadata', function() {
                    const duration = Math.round(audio.duration);
                    document.getElementById('duration').value = duration;

                    // Форматируем время
                    const minutes = Math.floor(duration / 60);
                    const seconds = duration % 60;
                    const formattedTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                    document.getElementById('durationDisplay').textContent = formattedTime;
                    document.getElementById('audioInfo').classList.remove('hidden');

                    URL.revokeObjectURL(audio.src);
                });
            }
        }

        // Обработка drag and drop
        coverPreview.addEventListener('dragover', (e) => {
            e.preventDefault();
            coverPreview.classList.add('border-indigo-500', 'border-2');
        });

        coverPreview.addEventListener('dragleave', () => {
            coverPreview.classList.remove('border-indigo-500', 'border-2');
        });

        coverPreview.addEventListener('drop', (e) => {
            e.preventDefault();
            coverPreview.classList.remove('border-indigo-500', 'border-2');

            if (e.dataTransfer.files.length) {
                coverInput.files = e.dataTransfer.files;
                previewCover({ target: coverInput });
            }
        });

        // Обновляем цвет при вводе названия
        titleInput.addEventListener('input', updateCoverColor);

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            if (titleInput.value) {
                updateCoverColor();
            }
        });
    </script>
@endsection
