<?php
/**
 * Created by PhpStorm.
 * User: Miha
 * Date: 31.12.2018
 * Time: 13:34
 */

namespace App\Services;


use App\Tag;

class TagService
{
    private $perPage = 6;

    public function all()
    {
        $tags = Tag::orderBy('tag', 'asc')->paginate($this->perPage);

        return $tags;
    }

    public function add($tag)
    {
        Tag::create(['tag'  => $tag]);

        return true;
    }

    public function getOne($id)
    {
        $tag = Tag::find($id);

        return $tag;
    }

    public function update($id, $data)
    {
        $count = Tag::where('id', $id)->update($data);

        return $count;
    }

    public function delete($id)
    {
        Tag::destroy([$id]);
    }

}