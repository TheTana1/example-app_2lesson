<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Repository\BookRepository;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private const PER_PAGE = 35;
    public function __construct(BookRepository $bookRepository)
    {
         $this->bookRepository = $bookRepository;
    }

    public function index(Request $request)
    {

        $books =  Book::query()
            ->with(['user:id,name,avatar',
                'user.avatar'])
            ->paginate($request->get(10, self::PER_PAGE));
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
