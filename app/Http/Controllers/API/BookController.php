<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Repository\BookRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    private const PER_PAGE = 35;
    public function __construct(BookRepository $bookRepository)
    {
         $this->bookRepository = $bookRepository;
    }

    public function index(Request $request): AnonymousResourceCollection
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
    public function store(BookRequest $request): BookResource
    {
        //title, user_slug вместо user_id
        return new BookResource($this->bookRepository->store($request));
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): BookResource
    {
        return new BookResource($book->load([
            'user:id,name,avatar',
            ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book): BookResource
    {
        return new BookResource($this->bookRepository->update($request, $book));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        return response()->json([
            'status'=> $this->bookRepository->destroy($book)? 'success': 'failure']) ;
    }
}
