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
        $tags = null;
        $userId = $user->id;

        $ps = new PostService();
        $ps->store($title, $file, $text, $tags, $userId);
        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'text' => $text,
            'user_id' => $userId
        ]);
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
        $file = null;
        $tags = null;
        $ps = new PostService();
        $ps->updatePost($post->id, $title, $text, $file, $tags, $post->user_id);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $title,
            'text' => $text
        ]);
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

    // привязка tag к посту
    // возможно, что даже и не стоит тестировать эту функцию, тк функционал
    // написан разработчиком, я про функцию attach. Т.к. я не придумываю её сам а всего-лишь использую уже готовую
    /** @test */
    public function tagAttach()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();
        // генерим теги
        factory(App\Tag::class, 10)->create();
        $ts = new TagService();
        $tagsArray = $ts->allDontPaginate();
        $tags = array_keys($this->getRandTags($tagsArray));
        $post->tags()->attach($tags);
        // проверяем есть ли теги в связующей таблице
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('posts_tags', [
                'post_id' => $post->id,
                'tag_id' => $tag
            ]);
        }
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
