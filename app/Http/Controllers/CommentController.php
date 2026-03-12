<?php

namespace App\Http\Controllers;


use App\Http\Requests\CommentRequest;

use App\Http\Requests\MusicRequest;
use App\Models\Comment;
use App\Repository\CommentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function __construct(private CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }


    public function store(CommentRequest $request): RedirectResponse
    {

        $model = match($request->type) {
            'music' => \App\Models\Music::find($request->id),
//            'post' => \App\Models\Post::find($id),
            default => null
        };
        $newComment = $this->commentRepository->store($request);
        $model->comments()->attach($newComment->id);
        return redirect()->route("{$request->type}.show",$request->id);

    }

    public function update(CommentRequest $request, Comment $comment): RedirectResponse
    {
        //dd($request);
        $this->commentRepository->update($request, $comment);
        return redirect()->route("{$request->type}.show", $request->id);
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        if ($this->commentRepository->destory($comment))
            return redirect()->route("{$request->type}.show", $request->id)
        ->with('success', 'Successfully deleted comment!');
        return redirect()->route("{$request->type}.show", $request->id)
        ->with('error', 'Failed to delete comment!');
    }



}
