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
        $books = Book::query()->with('user.avatar');

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

        $book = Book::with('user')->find($book->id);
        $user = $book->user; // один пользователь
        //dd($book);

        return view('books.show',compact('book','user'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(BookRequest $bookRequest)
    {

//        dd($bookRequest->all());

        $newBook = $this->bookRepository->store($bookRequest);
        return redirect()->route('books.show', $newBook)
            ->with('success', 'Книга удачно создана');
    }

    public function edit(Book $book)
    {
        return view('books.edit',compact('book'));
    }

    public function update(BookRequest $bookRequest, Book $book)
    {
        $updateResult = $this->bookRepository->update($bookRequest, $book);
        return redirect()->route('books.show',$updateResult)
            ->with('success','Книга успешно обновлена');
    }

    public function destroy(Book $book)
    {
        $deleteResult = $this->bookRepository->destroy($book);
        if($deleteResult)
            return redirect()->route('books.index')
        ->with('success','Книга успешно удалена');
        return redirect()->route('books.index')
        ->withErrors('error', 'Ошибка при удалении книги');
    }
}
