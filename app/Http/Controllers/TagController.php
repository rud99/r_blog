<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    private $tag;

    public function __construct(TagService $tagService)
    {
        $this->tag = $tagService;
    }

    public function index()
    {
        if (Auth::user()->is_admin) {
            $tags = $this->tag->all();

            return view('tags', ['tags' => $tags]);
        } else return abort(404);
    }

    public function storeTag(Request $request)
    {
        if (Auth::user()->is_admin) {
            $this->validate($request, [
                'tag' => 'required|min:3'
            ]);
            $tag = $request->input('tag');
            $this->tag->add($tag);

            return redirect()->route('tags');
        } else return abort(404);
    }

    public function editTag($id)
    {
        if (Auth::user()->is_admin) {
        $tag = $this->tag->getOne($id);

        return view('edittag', ['tag' => $tag]);
        } else return abort(404);
    }

    public function updateTag(Request $request)
    {
        $this->validate($request, [
            'tag' => 'required|min:3'
        ]);
        $id = $request->input('id');
        $newTag = $request->input('tag');
        $this->tag->update($id, ['tag' => $newTag]);

        return redirect()->route('tags');
    }

    public function deleteTag($id)
    {
        if (Auth::user()->is_admin) {
            $this->tag->delete($id);

            return redirect()->route('tags');
        } else return abort(404);
    }
}
