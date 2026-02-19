@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Книги
                </h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        Всего книг: <span class="font-semibold ml-1">{{ $books->total() }}</span>
                    </span>
                </p>
            </div>

            @auth
                <a href="{{ route('books.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700
                  text-white font-medium rounded-lg shadow-md transition-all duration-200
                  transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Добавить книгу
                </a>
            @endauth
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <!-- Основная строка фильтров -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                <!-- ФОРМА ФИЛЬТРОВ -->
                <form id="filter-form" method="GET" action="{{ route('books.index') }}">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Поиск по названию книги -->
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Поиск
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           name="search"
                                           placeholder="Название книги..."
                                           value="{{ request('search') }}"
                                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600
                                                  rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                                  text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400
                                                  transition-colors duration-200">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Фильтр по пользователю -->
                            <div class="w-full lg:w-48">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Пользователь
                                </label>
                                <select name="user_id"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600
                                               rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                               text-gray-900 dark:text-white transition-colors duration-200">
                                    <option value="">Все пользователи</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Диапазон дат создания -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                                        Сначала новые
                                    </option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                        Сначала старые
                                    </option>
                                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>
                                        По названию (А-Я)
                                    </option>
                                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>
                                        По названию (Я-А)
                                    </option>
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
                                <a href="{{ route('books.index') }}"
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

                <!-- Активные фильтры -->
                @if(request()->anyFilled(['search', 'user_id', 'date_from', 'date_to']) ||
                    (request('sort') && request('sort') != 'newest'))
                    <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900/30 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Активные фильтры:</span>

                            @if(request('search'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">
                                    Поиск: "{{ request('search') }}"
                                    <a href="{{ route('books.index', array_merge(request()->except(['search', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            @if(request('user_id'))
                                @php $selectedUser = $users->find(request('user_id')); @endphp
                                @if($selectedUser)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                                 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                                 border border-indigo-200 dark:border-indigo-800">
                                        Пользователь: {{ $selectedUser->name }}
                                        <a href="{{ route('books.index', array_merge(request()->except(['user_id', 'page']))) }}"
                                           class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </a>
                                    </span>
                                @endif
                            @endif

                            @if(request('date_from'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">
                                    С: {{ \Carbon\Carbon::parse(request('date_from'))->format('d.m.Y') }}
                                    <a href="{{ route('books.index', array_merge(request()->except(['date_from', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            @if(request('date_to'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                             bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400
                                             border border-indigo-200 dark:border-indigo-800">
                                    По: {{ \Carbon\Carbon::parse(request('date_to'))->format('d.m.Y') }}
                                    <a href="{{ route('books.index', array_merge(request()->except(['date_to', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
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
                                        @case('title_asc') По названию (А-Я) @break
                                        @case('title_desc') По названию (Я-А) @break
                                    @endswitch
                                    <a href="{{ route('books.index', array_merge(request()->except(['sort', 'page']))) }}"
                                       class="ml-2 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            <!-- Кнопка "Очистить все" -->
                            @if(request()->anyFilled(['search', 'user_id', 'date_from', 'date_to']) ||
                                (request('sort') && request('sort') != 'newest'))
                                <a href="{{ route('books.index') }}"
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                          bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                          border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600
                                          transition-colors">
                                    Очистить все
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Информация о количестве результатов -->
                <div class="px-5 py-3 bg-gray-50/50 dark:bg-gray-900/20 text-sm text-gray-600 dark:text-gray-400">
                    Найдено книг: <span
                        class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $books->total() }}</span>
                </div>
            </div>
        </div>



        <!-- Card с таблицей книг -->
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
                            ID
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Название книги
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Владелец
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Дата добавления
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($books as $book)
                        <tr
                            onclick="window.location='{{ route('books.show', $book) }}'"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 cursor-pointer group"
                        >
                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                #{{ $book->id }}
                            </td>

                            <!-- Название книги -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div
                                            class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                            {{ strtoupper(mb_substr($book->title, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                            {{ $book->title }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Владелец (user) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($book->user)
                                        <div class="flex-shrink-0 h-8 w-8">
                                            @if($book->user->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $book->user->avatar->path)))
                                                <img src="{{ asset($book->user->avatar->path) }}"
                                                     alt="{{ $book->user->name }}"
                                                     class="h-8 w-8 rounded-full object-cover">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                                            flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(mb_substr($book->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-2">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $book->user->name }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Пользователь удален</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Дата добавления -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $book->created_at->format('d.m.Y H:i') }}
                            </td>

                            <!-- Действия -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-4" onclick="event.stopPropagation()">
                                    @auth
{{--                                        @if(auth()->id() === $book->user_id || auth()->user()?)--}}
                                            <a href="{{ route('books.edit', $book) }}"
                                               class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition"
                                               title="Редактировать">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.828 2.828L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                                </svg>
                                            </a>

                                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Удалить пользователя {{ $user->name }}?')"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
                                                title="Удалить">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                                </button>
                                            </form>
{{--                                        @endif--}}
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                <div class="text-6xl mb-4">📚</div>
                                <p class="text-lg">Книги пока отсутствуют</p>
                                @auth
                                    <a href="{{ route('books.create') }}"
                                       class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Добавить первую книгу →
                                    </a>
                                @endauth
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($books->hasPages())
                <div class="px-6 py-5 border-t border-gray-200 dark:border-gray-700">
                    {{ $books->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Скрипт для обработки кликов и автосабмита -->
    <script>
        document.querySelectorAll('tbody tr[cursor-pointer]').forEach(row => {
            row.addEventListener('mousedown', (e) => {
                if (e.detail > 1) {
                    e.preventDefault();
                }
            });
        });

        // Автоматическая отправка формы при изменении select
        document.querySelectorAll('select[name="user_id"], select[name="sort"]').forEach(select => {
            select.addEventListener('change', function () {
                document.getElementById('filter-form').submit();
            });
        });
    </script>
@endsection
