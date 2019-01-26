<?php

namespace Tests\Unit;

use App;
use App\Services\CommentService;
use App\Services\PostService;
use App\Services\TagService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
//    use RefreshDatabase;

    protected function getUploadedFileObject()
    {
        $image_path = 'public/favicon.ico';
        $image = new UploadedFile($image_path, 'test.png', 'image/png', filesize($image_path), null, true);

        return $image;
    }

    protected function getRandTags(Array $tagsArray)
    {
        $tagCount = rand(0, 10);
        $tags = [];
        for ($i = 1; $i <= $tagCount; $i++) {
            $tags[] += $tagsArray[array_rand($tagsArray)];
        }

        return $tags;
    }

//    /** @test */
//    public function postCreate()
//    {
//        // нам нужен пользователь для создания поста
//        $user = factory(App\User::class)->create();
//        // причем он должен быть залогинен для добавления поста
//        $this->be($user);
//
//        $title = 'Test title';
//        $file = $this->getUploadedFileObject();
//        $text = 'Test text Test text Test text Test text';
//        $tags = null;
//
//        $pk = new PostService();
//        $pk->store($title, $file, $text, $tags);
//        $this->assertDatabaseHas('posts', [
//            'title' => $title,
//            'text' => $text,
//            'user_id' => $user->id
//        ]);
//    }
//
    /** @test */
    public function mainPage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Posts');
        $response->assertSee('Login');
        $response->assertSee('Register');
    }

    /** @test */
    public function postCreateAndShow()
    {
        $post = factory(App\Post::class)->create();
        $response = $this->get('/post/'.$post->id);
        $response->assertSee('<h1 align="center">'.$post->title.'</h1>');
        $response->assertSee($post->text);
        $this->assertDatabaseHas('posts', [
            'title' => $post->title,
            'text' => $post->text
        ]);
    }
//    /** @test */
//    public function postCreate()
//    {
//        // нам нужен пользователь для создания поста
//        $user = factory(App\User::class)->create();
//        // причем он должен быть залогинен для добавления поста
//        $this->be($user);
//
//        $title = 'Test title';
//        $file = $this->getUploadedFileObject();
//        $text = 'Test text Test text Test text Test text';
//        $tags = null;
//
//        $pk = new PostService();
//        $pk->store($title, $file, $text, $tags);
//        $this->assertDatabaseHas('posts', [
//            'title' => $title,
//            'text' => $text,
//            'user_id' => $user->id
//        ]);
//    }
//
//
//    /** @test */
//    public function tagCreateAndShow()
//    {
//        $user = factory(App\User::class)->create();
//        $user->setAdmin();
//        $this->be($user);
//        $tag = factory(App\Tag::class)->create();
//        $response = $this->get('/tags');
//        $response->assertSee('Tags');
//        $this->assertDatabaseHas('tags', [
//            'tag' => $tag->tag
//        ]);
//
//    }
//
//    /** @test */
//    public function tagDelete()
//    {
//        $user = factory(App\User::class)->create();
//        $user->setAdmin();
//        $this->be($user);
//        $tag = factory(App\Tag::class)->create();
//        $response = $this->get('/deletetag/'.$tag->id);
//        $response->assertRedirect('/tags');
//        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
//
//    }
//
//    /** @test */
//    public function tagUpdate()
//    {
//        $user = factory(App\User::class)->create();
//        $user->setAdmin();
//        $this->be($user);
//        $tag = factory(App\Tag::class)->create();
//
//        $newTagValue = str_random(7);
//        $tagService = new TagService();
//        $update = $tagService->update($tag->id, ['tag' => $newTagValue]);
//        $this->assertEquals($update, 1);
//        $tag = $tagService->getOne($tag->id);
//        $this->assertEquals($newTagValue, $tag->tag);
//    }
//
//    /** @test */
//    public function commentCreateAndShow()
//    {
//        $comment = factory(App\Comment::class)->create();
//        $response = $this->get('/post/'.$comment->post->id);
//        $response->assertSee('<h1 align="center">'.$comment->post->title.'</h1>');
//        $response->assertSee($comment->post->text);
//
//        // отображение всех комментов залогиненного пользователя
//        $response = $this->actingAs($comment->user)->get('/comments');
//        $response->assertSee('Comments');
//        $response->assertSee($comment->comment);
//        // удаление коммента
//        $response = $this->actingAs($comment->user)->get('/deletecomment/'.$comment->id);
//        $response->assertRedirect('/comments');
//        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
//    }
//
//    /** @test */
//    public function commentDelete()
//    {
//        $comment = factory(App\Comment::class)->create();
//        $response = $this->actingAs($comment->user)->get('/deletecomment/'.$comment->id);
//        $response->assertRedirect('/comments');
//        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
//    }
//
//    /** @test */
//    public function commentUpdate()
//    {
//        $comment = factory(App\Comment::class)->create();
//        $this->be($comment->user);
//
//        $newComment = str_random(500);
//        $commentService = new CommentService();
//        $update = $commentService->update($comment->id, ['comment' => $newComment]);
//        $this->assertEquals($update, 1);
//        $comment = $commentService->getOne($comment->id);
//        $this->assertEquals($newComment, $comment->comment);
//    }
//
//    /** @test */
//    public function postDelete()
//    {
//        $post = factory(App\Post::class)->create();
//        $response = $this->actingAs($post->user)->get('/deletepost/'.$post->id);
//        $response->assertRedirect('/');
//        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
//        $this->assertFileNotExists('/public/'.$post->image);
//    }
//
//
//
//    /** @test */
//    public function postUpdate()
//    {
//        factory(App\Tag::class, 5)->create();
//        $post = factory(App\Post::class)->create();
//        $this->be($post->user);
//
//        $image = $this->getUploadedFileObject();
//
//        $data = [
//            'title' => 'Test_title',
//            'image' => $image,
//            'text' => 'Test text Test text Test text',
//            'views' => 999
//        ];
//        $tagService = new TagService();
//        $tagsArray = $tagService->allDontPaginate();
//
//        $tags = $this->getRandTags($tagsArray);
//        $postService = new PostService();
//        $update = $postService->updatePost($post->id, $data['title'], $data['text'], $data['image'], $tags);
//        $post = $postService->getOne($post->id);
//        $this->assertTrue($update);
//        $this->assertEquals($data['title'], $post->title);
//        $this->assertEquals($data['text'], $post->text);
//    }
}
