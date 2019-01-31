<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 26.12.2018
 * Time: 13:49
 */

namespace App\Services;


use App\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    private $perPage = 6;

    public function add($postId, $comment, $userId)
    {
        Comment::create([
            'user_id'  => $userId,
            'post_id'  => $postId,
            'comment'  => $comment,
        ]);

        return true;
    }

    public function all()
    {
//        $comments = Comment::orderBy('updated_at', 'desc')->get()->all();
        $comments = Comment::orderBy('updated_at', 'desc')->paginate($this->perPage);

        return $comments;
    }

    public function getUsersComments()
    {
        $userId = Auth::id();
        $comments = Comment::where('user_id', $userId)->orderBy('updated_at', 'desc')->paginate($this->perPage);

        return $comments;
    }

    public function getOne($id)
    {
        $comment = Comment::find($id);

        return $comment;
    }

    public function isCommentAuthor($commentUserId)
    {
        if (Auth::id() == $commentUserId) $res = true;
        else $res = false;

        return $res;
    }

    public function delete($id, $userId)
    {
       $comment = $this->getOne($id);
       $commentUserId = $comment->user_id;
       if ($comment->user_id == $userId) {
           Comment::destroy([$id]);
           $res = true;
       } else $res = false;

       return $res;
    }

    public function update($id, $data)
    {
        $count = Comment::where('id', $id)->update($data);

        return $count;
    }
}