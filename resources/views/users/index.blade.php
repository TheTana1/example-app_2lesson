@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Пользователи
                </h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Активных: <span class="font-semibold ml-1">{{ $users->where('active', true)->count() }}</span>
                    </span>
                </p>
            </div>

            @auth
                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700
                  text-white font-medium rounded-lg shadow-md transition-all duration-200
                  transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Добавить пользователя
                </a>
            @endauth
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <!-- Основная строка фильтров -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                <!-- ФОРМА ФИЛЬТРОВ -->
                <form id="filter-form" method="GET" action="{{ route('users.index') }}">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Поиск по имени и email -->
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Поиск
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           name="search"
                                           placeholder="Имя или email..."
                                           value="{{ request('search') }}"
                                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600
                                                  rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                                  text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400
                                                  transition-colors duration-200">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Фильтр по статусу active (НОВЫЙ) -->
                            <div class="w-full lg:w-40">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Статус
                                </label>
                                <select name="active"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600
                                               rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                               text-gray-900 dark:text-white transition-colors duration-200">
                                    <option value='2'>Все</option>
                                    <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Активные</option>
                                    <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Неактивные</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Дата с -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Дата с
                                    </label>
                                    <input type="date"
                                           name="date_from"
                                           value="{{ request('date_from') }}"
                                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600
                      rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                      text-gray-900 dark:text-white transition-colors duration-200">
                                </div>

                                <!-- Дата по -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Дата по
                                    </label>
                                    <input type="date"
                                           name="date_to"
                                           value="{{ request('date_to') }}"
                                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600
                      rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                      text-gray-900 dark:text-white transition-colors duration-200">
                                </div>
                            </div>
                            <!-- Сортировка -->
                            <div class="w-full lg:w-48">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Сортировка
                                </label>
                                <select name="sort"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600
                                               rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                               text-gray-900 dark:text-white transition-colors duration-200">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>По имени (А-Я)</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>По имени (Я-А)</option>
                                </select>
                            </div>

                            <!-- Кнопки действий -->
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium
                                               rounded-lg shadow-md transition-all duration-200 transform hover:scale-105
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Применить
                                </button>
                                <a href="{{ route('users.index') }}"
                                   class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                          text-gray-700 dark:text-gray-300 font-medium rounded-lg shadow-md
                                          transition-all duration-200 transform hover:scale-105
                                          focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                    Сбросить
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Активные фильтры (вне формы) -->
                @if(request()->anyFilled(['search', 'active', 'date_filter', 'sort']) &&
                    (request('sort') != 'newest' || request('search') || request('active') !== '' || request('date_filter')))
                    <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900/30 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Активные фильтры:</span>

                            @if(request('search'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">
                                    Поиск: "{{ request('search') }}"
                                    <a href="{{ route('users.index', array_merge(request()->except(['search', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            @if(request('active')!=='2')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">

                                    {{ request('active') ? 'Активные' : 'Неактивные' }}

                                    <a href="{{ route('users.index', array_merge(request()->except(['active', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            @if(request('date_from'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">
                                    {{request('date_from')}}

                                    <a href="{{ route('users.index', array_merge(request()->except(['date_from', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            @if(request('date_to'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">
                                    {{request('date_to')}}

                                    <a href="{{ route('users.index', array_merge(request()->except(['date_to', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            @if(request('sort') && request('sort') != 'newest')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">
                                    @switch(request('sort'))
                                        @case('oldest') Сначала старые @break
                                        @case('name_asc') По имени (А-Я) @break
                                        @case('name_desc') По имени (Я-А) @break
                                    @endswitch
                                    <a href="{{ route('users.index', array_merge(request()->except(['sort', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            <!-- Кнопка "Очистить все" -->
                            @if(request()->anyFilled(['search', 'active', 'date_filter']) ||
                                (request('sort') && request('sort') != 'newest'))
                                <a href="{{ route('users.index') }}"
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                          bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                          border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600
                                          transition-colors">
                                    Очистить все
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Информация о количестве результатов -->
                <div class="px-5 py-3 bg-gray-50/50 dark:bg-gray-900/20 text-sm text-gray-600 dark:text-gray-400">
                    Найдено пользователей: <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $users->total() }}</span>
                </div>
            </div>
        </div>

        <!-- Card с таблицей пользователей -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
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
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Статус
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Имя
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Возраст
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Дата регистрации
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr
                            onclick="window.location='{{ route('users.show', $user) }}'"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 cursor-pointer group"
                        >
                            <!-- Колонка статуса (active) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                        Активен
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-1.5"></span>
                                        Неактивен
                                    </span>
                                @endif
                            </td>

                            <!-- Имя с аватаром -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div
                                            class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-sm group-hover:shadow-md transition-colors relative">
                                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}

                                            <!-- Индикатор на аватаре -->
                                            @if($user->active)
                                                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white dark:ring-gray-800 bg-green-500"></span>
                                            @else
                                                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-400"></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                            {{ $user->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-300">
                                    {{ $user->email }}
                                </div>
                            </td>

                            <!-- Возраст -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-300">
                                    {{ $user->age ?? '—' }}
                                </div>
                            </td>

                            <!-- Дата регистрации -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('d.m.Y H:i') }}
                            </td>

                            <!-- Действия -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-4" onclick="event.stopPropagation()">
                                    @auth
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition"
                                           title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.828 2.828L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                            </svg>
                                        </a>
                                    @endauth

                                    @auth
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="event.stopPropagation(); return confirm('Вы уверены, что хотите удалить пользователя {{ addslashes($user->name) }}?')"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
                                                    title="Удалить">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                <div class="text-6xl mb-4">😔</div>
                                <p class="text-lg">Пользователи пока отсутствуют</p>
                                @auth
                                    <a href="{{ route('users.create') }}"
                                       class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Добавить первого пользователя →
                                    </a>
                                @endauth
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="px-6 py-5 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Скрипт для обработки кликов -->
    <script>
        document.querySelectorAll('tbody tr[cursor-pointer]').forEach(row => {
            row.addEventListener('mousedown', (e) => {
                if (e.detail > 1) {
                    e.preventDefault();
                }
            });
        });

        // Автоматическая отправка формы при изменении select (опционально)
        document.querySelectorAll('select[name="active"], select[name="date_filter"], select[name="sort"]').forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
    </script>
@endsection
