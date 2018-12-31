<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $comment;

    public function __construct(CommentService $connentService)
    {
        $this->comment = $connentService;
    }

    public function index()
    {
//        $comments = $this->comment->all();
        $comments = $this->comment->getUsersComments();
//        $comments = Comment::orderBy('updated_at', 'desc')->paginate(3);

        return view('comments', ['comments' => $comments]);
    }

    public function storeComment(Request $request)
    {
        $this->validate($request, [
            'post_id' => 'required',
            'comment' => 'required|min:10'
        ]);
        $postId  = $request->input('post_id');
        $comment = $request->input('comment');
        $this->comment->add($postId, $comment);

        return redirect('/post/'.$postId);

    }

    public function deleteComment($id)
    {
        $comment = $this->comment->getOne($id);
        $commentUserId = $comment->user_id;
        if ($this->comment->isCommentAuthor($commentUserId)) {
            $this->comment->delete($id);
            return redirect('/comments');
        } else return abort(404);
    }

    public function editComment($id)
    {
        $comment = $this->comment->getOne($id);
        $commentUserId = $comment->user_id;
        if ($this->comment->isCommentAuthor($commentUserId)) {
            return view('editcomment', ['comment' => $comment]);
        } else return abort(404);
    }

    public function updateComment(Request $request)
    {
        $id = $request->input('id');
        $newComment = $request->input('id');
        $comment = $this->comment->getOne($id);
        $commentUserId = $comment->user_id;
        if ($this->comment->isCommentAuthor($commentUserId)) {
            $this->comment->update($id, ['comment' => $newComment]);
            return redirect('/comments');
        } else return abort(404);
    }
}