<?php

namespace App\Http\Controllers;


use App\Repository\CommentRepository;


class CommentController extends Controller
{
    public function __construct(private CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

}
