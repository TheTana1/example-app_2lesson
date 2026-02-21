<?php

namespace App\Repository;


use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\User;

class BookRepository
{
    final function store(BookRequest $request): Book
    {

        $user = User::slug($request->user_slug)->firstOrFail();
        $validated = $request->validated();
        return Book::query()->create([
                'title' => $validated['title'],
                'user_id' => $user->id,
            ]
        );
    }
    final function update(BookRequest $request, Book $book): Book
    {
        $user = User::slug($request->user_slug)->firstOrFail();
        $validated = $request->validated();
        $book->update([
            'title' => $validated['title'],
            'user_id'=>$user->id]);
        return $book->refresh();
    }

    final function destroy(Book $book): bool
    {
        return $book->delete();
    }
}
