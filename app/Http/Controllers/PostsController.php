<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use App;

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
//        factory(App\Post::class, 10)->create();
        $posts = $this->post->all();

        return view('main', ['posts' => $posts]);
    }

    public function showPost($id)
    {
        $post = $this->post->getOne($id);
        $this->post->update($id, ['views' => $post->views + 1]);

        return view('post', ['post' => $post]);
    }

    public function add()
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'image' => 'required|image',
            'text' => 'required|min:5'
        ]);
        $userId = Auth::id();
        $title = $request->input('title');
        $filename = $request->file('image');
        $text = $request->input('text');
        $tags = $request->input('tags');
        $this->post->store($title, $filename, $text, $tags, $userId);

        return redirect('/');
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $image = $request->file('image');
        $text = $request->input('text');
        $tags = $request->input('tags');
        try {
            $this->post->updatePost($id, $title, $text, $image, $tags);
            return redirect('/');
        } catch (Exception $exception) {
            return abort(404);
        }
    }

    public function delete($id)
    {
        $res = $this->post->deletePost($id);
        if ($res) return redirect('/');
            else return abort(404);
    }

    public function showPostWidthTags($tagId)
    {
        $tag = $this->tag->getOne($tagId);

        return view('tagposts', ['tag' => $tag]);
    }


}
