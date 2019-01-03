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
            'image' => 'required|image',
            'text' => 'required|min:5'
        ]);
        $title = $request->input('title');
        $filename = $request->file('image');
        $text = $request->input('text');
        $tags = $request->input('tags');
        $this->post->storePostWidthTags($title, $filename, $text, $tags);

        return redirect('/');
    }

    public function updatePost(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $image = $request->file('image');
        $text = $request->input('text');
        $tags = $request->input('tags');
        $res = $this->post->updatePostWidthTagsAndImage($id, $title, $text, $image, $tags);
        if ($res) return redirect('/');
            else return abort(404);
    }

    public function deletePost($id)
    {
        $res = $this->post->fullDeletePost($id);
        if ($res) return redirect('/');
            else return abort(404);
    }

    public function showPostWidthTags($tagId)
    {
        $tag = $this->tag->getOne($tagId);

        return view('tagposts', ['tag' => $tag]);
    }
}
