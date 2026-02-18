<?php

namespace App\Http\Controllers\Web;

use App\Filters\BookFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\User;
use App\Repository\BookRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct(
        private BookRepository       $bookRepository,
        private readonly BookFilters $bookFilters)
    {
        $this->bookRepository = $bookRepository;
    }

    public function index(Request $request): View
    {
        $books = Book::query()->with('user');

        return view('books.index', [
            'books' => $this->bookFilters
                ->apply($request, $books)
                ->paginate(35)
                ->withQueryString(),
            'users' => User::all(['id', 'name'])
        ]);
    }

    public function show(Book $book)
    {
        $book->load('user');
        return view('books.show', [
            'book'=>$book]);
    }

    public function create()
    {

    }

    public function store(BookRequest $bookRequest)
    {

    }

    public function edit(Book $book)
    {

    }

    public function update(BookRequest $bookRequest, Book $book)
    {

    }

    public function destroy(Book $book)
    {

    }
}
