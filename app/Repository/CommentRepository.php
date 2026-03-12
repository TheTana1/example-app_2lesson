<?php

namespace App\Repository;


use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentRepository
{
    final function store(CommentRequest $request): Comment
    {

        $validated = $request->validated();
        return Comment::query()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);
    }

    final function update(CommentRequest $request, Comment $comment): bool
    {
        $validated = $request->validated();
        return $comment->update($validated);
    }

    public function destory(Comment $comment): bool
    {
        return $comment->delete();
    }

}
