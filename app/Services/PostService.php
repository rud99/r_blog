<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 21.12.2018
 * Time: 8:59
 */

namespace App\Services;

use App\Comment;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    private $perPage = 6;

    public function all()
    {
//        $posts = Post::orderBy('updated_at', 'desc')->get()->all();
        $posts = Post::orderBy('created_at', 'desc')->paginate($this->perPage);

        return $posts;
    }

    public function add($title, $image, $text, $userId)
    {
        $filename = $image->store('uploads');
        $postId = Post::create([
            'title' => $title,
            'image' => $filename,
            'text'  => $text,
            'user_id'  => $userId
        ]);

        return $postId->id;
    }

    public function getOne($id)
    {
        $post = Post::find($id);

        return $post;
    }

    public function update($id, $data)
    {
        $count = Post::where('id', $id)->update($data);

        return $count;
    }

    public function isPostAuthor($postUserId)
    {
        if (Auth::id() == $postUserId) $res = true;
        else $res = false;

        return $res;
    }

    public function delete($id)
    {
        Post::destroy([$id]);
    }

    public function deleteImage($image)
    {
        Storage::delete($image);
    }

    public function deletePostComments($postId)
    {
        Comment::where('post_id', $postId)->delete();
    }

    /**
     * Удаляем теги, привязанные к посту поста
     * @param $post
     */
    public function deletePostTags($post)
    {
        $tags = $post->tags->pluck('id');
        $post->tags()->detach($tags);

        return true;
    }

    public function updateTags($postId, $tags)
    {
        PostTag::where('post_id', $postId)->delete();
        if ($tags) $this->storeTags($postId, $tags);

        return true;
    }

    /**
     * Сохраняем пост
     * @param $title
     * @param $filename
     * @param $text
     * @param $tags
     * @return bool
     */
    public function store($title, $filename, $text, $tags, $userId)
    {
        $postId = $this->add($title, $filename, $text, $userId);
        if ($tags) {
            $post = Post::find($postId);
            $tags = array_keys($tags);
//            $post->tags()->attach($tags, ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            $post->tags()->attach($tags);
        }

        return $postId;
    }

    public function updatePost($id, $title, $text, $image, $tags, $userId)
    {
        $post = $this->getOne($id);
//        if ($this->isPostAuthor($post->user_id)) {
            if ($post->user_id == $userId) {
            $data = [];
            // картинку изменили, выбрали новую в форме
            if (!is_null($image)) {
                $filename = $image->store('uploads');
                $this->deleteImage($post->image);
                $data = ['image' => $filename];
            }
            $data += [
                'title' => $title,
                'text' => $text
            ];
            $this->update($id, $data);
            if ($tags) $tags = array_keys($tags);
            $post->tags()->sync($tags);
            $res = true;
        } else $res = false;

        return $res;
    }


    public function deletePost($id, $userId)
    {
        $post = $this->getOne($id);
//        dd($post);
        $postUserId = $post->user_id;
        $image = $post->image;
//        if ($this->isPostAuthor($postUserId)) {
        if ($post->user_id == $userId) {
            $this->delete($id);
            $this->deleteImage($image);
            $this->deletePostComments($id);
            $this->deletePostTags($post);
//            $tags = $post->tags->pluck('id');
//            $post->tags()->detach($tags);
            $res = true;
        } else $res = false;

        return $res;
    }
}