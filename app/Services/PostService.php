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
use App\PostTag;
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

    public function add($title, $image, $text)
    {
        $filename = $image->store('uploads');
        $userId = Auth::id();
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

    public function storeTags($postId, $tags)
    {
        foreach ($tags as $key => $value) {
            PostTag::create(['post_id' => $postId, 'tag_id' => $key]);
        }

        return true;
    }

    public function deletePostTags($postId)
    {
        PostTag::where('post_id', $postId)->delete();
    }

    public function updateTags($postId, $tags)
    {
        PostTag::where('post_id', $postId)->delete();
        $this->storeTags($postId, $tags);

        return true;
    }


    public function storePostWidthTags($title, $filename, $text, $tags)
    {
        $postId = $this->add($title, $filename, $text);
        $this->storeTags($postId, $tags);

        return true;
    }

    public function updatePostWidthTagsAndImage($id, $title, $text, $image, $tags)
    {
        $post = $this->getOne($id);
        if ($this->isPostAuthor($post->user_id)) {
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
            if (!is_null($tags)) $this->updateTags($id, $tags);
            $res = true;
        } else $res = false;

        return $res;
    }


    public function fullDeletePost($id)
    {
        $post = $this->getOne($id);
        $postUserId = $post->user_id;
        $image = $post->image;
        if ($this->isPostAuthor($postUserId)) {
            $this->delete($id);
            $this->deleteImage($image);
            $this->deletePostComments($id);
            $this->deletePostTags($id);
            $res = true;
        } else $res = false;

        return $res;
    }
}