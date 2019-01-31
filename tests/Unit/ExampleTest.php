<?php

namespace Tests\Unit;

use App;
use App\Services\CommentService;
use App\Services\PostService;
use App\Services\TagService;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function getUploadedFileObject($fileName)
    {
        $image_path = 'public/' . $fileName;
        $image = new UploadedFile($image_path, $fileName, 'image/jpg', filesize($image_path), null, true);

        return $image;
    }

    protected function getRandTags(Array $tagsArray)
    {
        $tagCount = rand(1, 10);
        $tags = [];
        for ($i = 1; $i <= $tagCount; $i++) {
            $tags[] += $tagsArray[array_rand($tagsArray)];
        }
        $tagNew = [];
        foreach ($tags as $tag) {
            $tagNew[$tag] = 'on';
        }

        return $tagNew;
    }
    // логин пользователя
    /** @test */
    public function loginUser()
    {
        // нам нужен пользователь
        $user = factory(App\User::class)->create();
        $data = [
            'email' => $user->email,
            'password' => 'secret'
        ];
        $remember = 1;
        Auth::attempt($data, $remember);

        $this->assertAuthenticatedAs($user);
    }

    // регистрация пользователя
    /** @test */
    public function registerUser()
    {
        $data = [
            'email' => 'jphn@dmail.com',
            'name' => 'John Do',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        User::register($data);

        $this->assertDatabaseHas('users', [
            'email' => 'jphn@dmail.com',
            'name' => 'John Do'
        ]);
    }

    // создание поста
    /** @test */
    public function postCreate()
    {
        // нам нужен пользователь для создания поста
        $user = factory(App\User::class)->create();

        $title = 'Test title';
        $file = $this->getUploadedFileObject('03.jpg');
        $text = 'Test text Test text Test text Test text';
        // зачитываем все теги
        factory(App\Tag::class, 10)->create();
        $ts = new TagService();
        $tagsArray = $ts->allDontPaginate();
        $tags = $this->getRandTags($tagsArray);
//        dd($tags);
        $userId = $user->id;

        $ps = new PostService();
        $postId = $ps->store($title, $file, $text, $tags, $userId);
        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'text' => $text,
            'user_id' => $userId
        ]);
        // проверяем есть ли теги в связующей таблице
        foreach (array_keys($tags) as $tag) {
            $this->assertDatabaseHas('posts_tags', [
                'post_id' => $postId,
                'tag_id' => $tag
            ]);
        }
    }

    // редактир поста

    /** @test */
    public function postEdit()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();
        $title = 'Updated test title';
//        $file = $this->getUploadedFileObject('03.jpg');
        $text = 'Updated UpdatedUpdatedUpdated Test text Test text Test text Test text';
        // зачитываем все теги
        factory(App\Tag::class, 10)->create();
        $ts = new TagService();
        $tagsArray = $ts->allDontPaginate();
        $tags = $this->getRandTags($tagsArray);
        $file = null;
        $ps = new PostService();
        $ps->updatePost($post->id, $title, $text, $file, $tags, $post->user_id);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $title,
            'text' => $text
        ]);
        // проверяем есть ли теги в связующей таблице
        foreach (array_keys($tags) as $tag) {
            $this->assertDatabaseHas('posts_tags', [
                'post_id' => $post->id,
                'tag_id' => $tag
            ]);
        }
    }

    // удаление поста

    /** @test */
    public function postDelete()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();
        $ps = new PostService();
        $ps->deletePost($post->id, $post->user_id);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('posts_tags', ['post_id' => $post->id]);
        $this->assertDatabaseMissing('comments', ['post_id' => $post->id]);
        $this->assertFileNotExists($post->image);
    }

    // создание коммента

    /** @test */
    public function commentCreate()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();

        $comment = 'Test comment';
        $postId = $post->id;
        $userId = $post->user_id;

        $cs = new CommentService();
        $cs->add($postId, $comment, $userId);
        $this->assertDatabaseHas('comments', [
            'post_id' => $postId,
            'user_id' => $userId,
            'comment' => $comment
        ]);
    }

    // редакт коммента

    /** @test */
    public function commentEdit()
    {
        // нам нужен коммент
        $comment = factory(App\Comment::class)->create();
        $newComment = 'Updated comment';
        $cs = new CommentService();
        $cs->update($comment->id, ['comment' => $newComment]);
        $this->assertDatabaseHas('comments', [
            'post_id' => $comment->post_id,
            'user_id' => $comment->user_id,
            'comment' => $newComment
        ]);
    }

    // удаление коммента

    /** @test */
    public function commentDelete()
    {
        // нам нужен коммент
        $comment = factory(App\Comment::class)->create();
        $cs = new CommentService();
        $cs->delete($comment->id, $comment->user_id);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    // создание tag

    /** @test */
    public function tagCreate()
    {
        $tagName = 'Test tag';
        $ts = new TagService();
        $ts->add($tagName);
        $this->assertDatabaseHas('tags', [
            'tag' => $tagName
        ]);
    }

    // изменение tag

    /** @test */
    public function tagUpdate()
    {
        $tag = factory(App\Tag::class)->create();
        $newTagValue = str_random(7);
        $ts = new TagService();
        $ts->update($tag->id, ['tag' => $newTagValue]);

        $this->assertDatabaseHas('tags', [
            'tag' => $newTagValue
        ]);
    }

    // удаление тега

    /** @test */
    public function tagDelete()
    {
        $tag = factory(App\Tag::class)->create();
        $ts = new TagService();
        $ts->delete($tag->id);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);

    }
}
