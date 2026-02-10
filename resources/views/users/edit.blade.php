@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">

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

                <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        <!-- Аватар + имя -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                            <div class="flex-shrink-0">
                                <div id="avatarPreview" class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                            flex items-center justify-center text-white font-bold text-3xl shadow-md
                                            cursor-pointer overflow-hidden border-4 border-white dark:border-gray-700">
                                    @if($user->avatar && file_exists(public_path($user->avatar->path)))
                                        <img id="avatarImage"
                                             src="{{ asset($user->avatar->path) }}"
                                             alt="{{ $user->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div id="avatarLetter" class="h-full w-full flex items-center justify-center text-5xl">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3 flex flex-col items-center space-y-2">
                                    <label for="avatar"
                                           class="cursor-pointer inline-flex items-center px-3 py-1.5
                                                      bg-indigo-100 dark:bg-indigo-900 hover:bg-indigo-200
                                                      dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300
                                                      text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Загрузить фото
                                    </label>

                                    <button type="button"
                                            id="removeAvatarBtn"
                                            class="{{ $user->avatar && file_exists(public_path($user->avatar->path)) ?  : 'hidden' }}
                                                   text-xs text-gray-500 dark:text-gray-400
                                                   hover:text-red-500 dark:hover:text-red-400 transition">
                                        Удалить фото
                                    </button>

                                    <!-- Скрытое поле для отметки удаления аватара -->
                                    <input type="hidden" name="remove_avatar" id="removeAvatarInput" value="0">
                                </div>

                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">
                                    JPEG, PNG, не более 2MB
                                </div>

                                <!-- Скрытый input для загрузки файла -->
                                <input type="file"
                                       name="avatar"
                                       id="avatar"
                                       accept="image/jpeg,image/png,image/jpg"
                                       class="hidden">

                                @error('avatar')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400 text-center">{{ $message }}</p>
                                @enderror
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

    <script>
        // Цвета для аватаров
        const avatarColors = [
            'from-blue-500 to-cyan-600',
            'from-green-500 to-emerald-600',
            'from-amber-500 to-yellow-600',
            'from-rose-500 to-pink-600',
            'from-violet-500 to-purple-600',
            'from-indigo-500 to-purple-600',
        ];

        // Получаем элементы DOM
        const avatarPreview = document.getElementById('avatarPreview');
        let avatarImage = document.getElementById('avatarImage');
        const avatarLetter = document.getElementById('avatarLetter');
        const avatarInput = document.getElementById('avatar');
        const removeAvatarBtn = document.getElementById('removeAvatarBtn');
        const removeAvatarInput = document.getElementById('removeAvatarInput');
        const nameInput = document.getElementById('name');
        const avatarLabel = document.querySelector('label[for="avatar"]');

        // Обработчик клика по превью для выбора файла

        avatarPreview.addEventListener('click', (e) => {
            e.stopPropagation();
            avatarInput.click();
        });

        // Функция для предпросмотра выбранной аватарки
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                //avatarPreview.classList.
                // Проверка размера файла (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Размер файла не должен превышать 2MB.');
                    avatarInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Создаем изображение если его нет

                    if (!avatarImage) {
                        console.log('создаём image');
                        avatarImage = document.createElement('img');
                        avatarImage.id = 'avatarImage';

                        avatarImage.className = 'w-full h-full object-cover';

                        avatarPreview.appendChild(avatarImage);

                    }
                    avatarImage.classList.remove('hidden');
                    avatarImage.src = e.target.result;
                    console.log(avatarImage);
                    // Скрываем букву
                    if (avatarLetter) {
                        avatarLetter.classList.add('hidden');
                    }

                    // Сбрасываем флаг удаления
                    removeAvatarInput.value = '0';

                    // Показываем кнопку удаления
                    removeAvatarBtn.classList.remove('hidden');

                    // Убираем градиентный фон
                    avatarPreview.classList.remove('bg-gradient-to-br', 'from-indigo-500', 'to-purple-600');
                    avatarColors.forEach(color => {
                        const [from, to] = color.split(' ');
                        avatarPreview.classList.remove(from, to);
                    });
                };
                reader.readAsDataURL(file);
            }
        }

        // Обработчик загрузки файла
        avatarInput.addEventListener('change', previewAvatar);

        // Функция для удаления аватарки
        removeAvatarBtn.addEventListener('click', (e) => {
            e.stopPropagation();

            // Сбрасываем input файла
            avatarInput.value = '';

            // Удаляем изображение
            if (avatarImage) {
                avatarImage.classList.add('hidden');
                avatarImage.src = '';
                console.log(avatarImage);
            }

            // Показываем букву
            if (avatarLetter) {
                avatarPreview.classList.add('bg-gradient-to-br', 'from-indigo-500', 'to-purple-600');
                avatarLetter.classList.remove('hidden');
            }

            // Восстанавливаем градиент
            updateAvatarLetter();

            // Устанавливаем флаг удаления
            removeAvatarInput.value = '1';

            // Скрываем кнопку удаления
            removeAvatarBtn.classList.add('hidden');
        });

        // Функция для обновления буквы аватара
        function updateAvatarLetter() {
            const name = nameInput.value.trim();
            let firstLetter = '?';

            if (name.length > 0) {
                firstLetter = name.charAt(0).toUpperCase();
            }

            if (avatarLetter) {
                avatarLetter.textContent = firstLetter;
            }

            // Обновляем градиент только если нет изображения
            if (!avatarImage || avatarImage.classList.contains('hidden') || !avatarImage.src) {
                // Выбираем цвет на основе имени
                let hash = 0;
                for (let i = 0; i < name.length; i++) {
                    hash = name.charCodeAt(i) + ((hash << 5) - hash);
                }
                const colorIndex = Math.abs(hash) % avatarColors.length;
                const selectedColor = avatarColors[colorIndex];
                const [fromColor, toColor] = selectedColor.split(' ');

                // Удаляем все градиентные классы
                avatarColors.forEach(color => {
                    const [from, to] = color.split(' ');
                    avatarPreview.classList.remove(from, to);
                });

                // Добавляем новые классы градиента
                avatarPreview.classList.add('bg-gradient-to-br', fromColor, toColor);
            }
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            // Обновляем букву аватара
            updateAvatarLetter();

            // Обработчик изменения имени
            nameInput.addEventListener('input', updateAvatarLetter);
        });

        // Обработка drag and drop
        avatarPreview.addEventListener('dragover', (e) => {
            e.preventDefault();
            avatarPreview.classList.add('border-indigo-500', 'border-2');
        });

        avatarPreview.addEventListener('dragleave', () => {
            avatarPreview.classList.remove('border-indigo-500', 'border-2');
        });

        avatarPreview.addEventListener('drop', (e) => {
            e.preventDefault();
            avatarPreview.classList.remove('border-indigo-500', 'border-2');

            if (e.dataTransfer.files.length) {
                avatarInput.files = e.dataTransfer.files;
                const event = new Event('change');
                avatarInput.dispatchEvent(event);
            }
        });
    </script>
@endsection
