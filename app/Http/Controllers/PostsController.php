<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    private $post;
    private $tag;

    public function __construct(PostService $postService, TagService $tagService)
    {
        $this->post = $postService;
        $this->tag = $tagService;
    }

    public function index()
    {
        $posts = $this->post->all();

        return view('main', ['posts' => $posts]);
    }

    public function showPost($id)
    {
        $post = $this->post->getOne($id);
        $this->post->update($id, ['views' => $post->views + 1]);

        return view('post', ['post' => $post]);
    }

    public function addPost()
    {
        $tags = $this->tag->all();
        return view('addpost', ['tags' => $tags]);
    }

    public function editPost($id)
    {
        $post = $this->post->getOne($id);
        if ($this->post->isPostAuthor($post->user_id)) {
            $tags = $this->tag->all();

            return view('editpost', ['post' => $post, 'tags' => $tags]);
        } else
                return abort(404);
    }

    public function storePost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'image' => 'image',
            'text' => 'required|min:5'
        ]);
        $title = $request->input('title');
        $filename = $request->file('image');
        $text = $request->input('text');
        $tags = $request->input('tags');
        $postId = $this->post->add($title, $filename, $text);

        $this->post->storeTags($postId, $tags);

        return redirect('/');
    }

    public function updatePost(Request $request)
    {

        $id = $request->input('id');
        $user_id = $request->input('user_id');
        $title = $request->input('title');
        $image = $request->file('image');
        $text = $request->input('text');
        $tags = $request->input('tags');
        $data = [];
        if ($this->post->isPostAuthor($user_id)) {
            if (!is_null($image)) {
                $filename = $image->store('uploads');
                $post = $this->post->getOne($id);
                $this->post->deleteImage($post->image);
                $data = ['image' => $filename];
            }

            $data += [
                'title' => $title,
                'text' => $text
            ];

            $this->post->update($id, $data);
            $this->post->updateTags($id, $tags);

            return redirect('/');
        } else return abort(404);
    }

    public function deletePost($id)
    {
        $post = $this->post->getOne($id);
        $postUserId = $post->user_id;
        $image = $post->image;
        if ($this->post->isPostAuthor($postUserId)) {
            $this->post->delete($id);
            $this->post->deleteImage($image);
            $this->post->deletePostComments($id);
            $this->post->deletePostTags($id);
            return redirect('/');
        } else return abort(404);

    }

    public function showPostWidthTags($tagId)
    {
        $tag = $this->tag->getOne($tagId);

        return view('tagposts', ['tag' => $tag]);
    }
}
