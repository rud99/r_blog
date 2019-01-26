<?php

namespace Tests\Unit;

use App;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyFuncTest extends TestCase
{
//    use RefreshDatabase;

    protected function getUploadedFileObject($fileName)
    {
        $image_path = 'public/'.$fileName;
        $image = new UploadedFile($image_path, $fileName, 'image/jpg', filesize($image_path), null, true);

        return $image;
    }

    // доступ к главной странице незарегистрированного пользователя
    /** @test */
    public function mainPageNotRegisterUser()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Posts');
        $response->assertSee('Home');
        $response->assertSee('Login');
        $response->assertSee('Register');
        $response->assertDontSee('Logout');
    }

    // просмотр поста незарегистрированным пользователем
    /** @test */
    public function postViewNotRegisterUser()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();

        $response = $this->get('/post/'.$post->id);
        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee($post->text);
        $response->assertSee($post->user->name);
        $response->assertSee('Go Back <');
        $response->assertSee('Comments:');
    }

    // открываем страницу регистрации
    /** @test */
    public function openRegisterUserPage()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Register');
        $response->assertSee('Name');
        $response->assertSee('E-Mail Address');
        $response->assertSee('Password');
        $response->assertSee('Password');
        $response->assertSee('Confirm Password');
    }

    // регистрация пользователя
    /** @test */
/*    public function registerUser()
    {
        $data = [
            'email' => 'jphn@dmail.com',
            'name' => 'John Do',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $response = $this->post('/registrate', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }*/

    // открываем страницу логина
    /** @test */
    public function openLoginUserPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('E-Mail Address');
        $response->assertSee('Password');
        $response->assertSee('Remember Me');
    }

    // регистрация пользователя
    /** @test */
    public function loginUser()
    {
        $user = factory(App\User::class)->create();
        $data = [
            'email' => $user->email,
            'password' => 'secret', // в UserFactory.php именно такое поле присваивается новому фейковому пользователю
            'remember' => 'on'
        ];
        $response = $this->post('/authenticate', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    // доступ к главной зарегистрированного пользователя
    /** @test */
    public function mainPageRegisterUser()
    {
        $user = factory(App\User::class)->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Posts');
        $response->assertSee('Add post');
        $response->assertSee('Manage my comments');
        $response->assertSee('Logout');
        $response->assertSee('User: <b>'.$user->name.'</b>');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }

    function testUserLogout()
    {
        $user = factory(App\User::class)->create();
        $response = $this->actingAs($user)->get('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertDontSee('User:');
        $response->assertDontSee('Logout');
    }

    // просмотр поста зарегистрированным пользователем
    /** @test */
    public function postViewRegisterUser()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();
        // нам нужен юзер
        $user = factory(App\User::class)->create();

        $response = $this->actingAs($user)->get('/post/'.$post->id);
        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee($post->text);
        $response->assertSee($post->user->name);
        $response->assertSee('Go Back <');
        $response->assertSee('Comments:');
    }

    // оставляем коммент зареганным пользователем
    /** @test */
    public function addCommentRegisterUser()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();
        // нам нужен юзер
        $user = factory(App\User::class)->create();
        $data = [
            'comment' => 'My test comment',
            'post_id' => $post->id,
            'user_id' => $user->id
        ];
        $response = $this->actingAs($user)->post('/storecomment', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/post/'.$post->id);
    }

    // открываем страницу добавления поста зарег пользователем
    /** @test */
    public function openAddPostPageRegisterUser()
    {
        // нам нужен юзер
        $user = factory(App\User::class)->create();

        $response = $this->actingAs($user)->get('/addpost');
        $response->assertStatus(200);
        $response->assertSee('Add new Post');
        $response->assertSee('Post title');
        $response->assertSee('Post text');
        $response->assertSee('Tags');
    }
    // добавляем пост зареганным пользователем
    /** @test */
    public function addPostRegisteredUser()
    {
        // нам нужен юзер
        $user = factory(App\User::class)->create();
        $data = [
            'title' => 'My test title 111',
            'image' => $this->getUploadedFileObject('03.jpg'),
            'text' => 'My test text My test text My test text My test textMy test textMy test text'
        ];
        $response = $this->actingAs($user)->post('/storepost', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    // открытие страницы редактирования поста зареганным пользователем
    /** @test */
    public function openPostEditRegisteredUser()
    {
        // нам нужен пост и его пользователь
        $post = factory(App\Post::class)->create();

        $response = $this->actingAs($post->user)->get('/editpost/'.$post->id);
        $response->assertStatus(200);
        $response->assertSee('Edit Post');
        $response->assertSee('Post title');
        $response->assertSee('Post text');
        $response->assertSee('Tags');
    }

    // апдейтим пост зареганным пользователем
    /** @test */
    public function updatePostRegisteredUser()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();
        $data = [
            'id' => $post->id,
            'title' => 'My updated test title',
            'image' => $this->getUploadedFileObject('08.jpg'),
            'text' => 'My updated testupdated text Myupdated test text Myupdated test text My test textMy test textMy test text'
        ];
        $response = $this->actingAs($post->user)->post('/updatepost', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    // удаление поста зареганным пользователем
    /** @test */
    public function deletePostRegisteredUser()
    {
        // нам нужен пост
        $post = factory(App\Post::class)->create();
        $response = $this->actingAs($post->user)->get('/deletepost/'.$post->id);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    // открытие страницы управления комментариями
    /** @test */
    public function openCommentsRegisteredUser()
    {
        // нам нужен юзер
        $user = factory(App\User::class)->create();

        $response = $this->actingAs($user)->get('/comments');
        $response->assertStatus(200);
        $response->assertSee('Comments');
        $response->assertSee('Comment');
        $response->assertSee('Post');
        $response->assertSee('Up. Date');
        $response->assertSee('Actions');
    }

    // открытие страницы редактирования коммента зарег пользователем
    /** @test */
    public function openCommentEditRegisteredUser()
    {
        // нам нужен коммент
        $comment = factory(App\Comment::class)->create();

        $response = $this->actingAs($comment->user)->get('/editcomment/'.$comment->id);
        $response->assertStatus(200);
        $response->assertSee('Edit Comment');
        $response->assertSee('Comment');
        $response->assertSee($comment->comment);
    }

    // апдейтим коммента зареганным пользователем
    /** @test */
    public function updateCommentRegisteredUser()
    {
        // нам нужен коммент
        $comment = factory(App\Comment::class)->create();

        $data = [
            'id' => $comment->id,
            'comment' => 'My updated comment'
        ];
        $response = $this->actingAs($comment->user)->post('/updatecomment', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/comments');
    }

    // удаление коммента зареганным пользователем
    /** @test */
    public function deleteCommentRegisteredUser()
    {
        // нам нужен коммент
        $comment = factory(App\Comment::class)->create();
        $response = $this->actingAs($comment->user)->get('/deletecomment/'.$comment->id);
        $response->assertStatus(302);
        $response->assertRedirect('/comments');
    }

    // доступ к главной admin пользователя
    /** @test */
    public function mainPageAdminUser()
    {
        $user = factory(App\User::class)->create();
        $user->setAdmin();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Posts');
        $response->assertSee('Add post');
        $response->assertSee('Manage tags');
        $response->assertSee('Manage my comments');
        $response->assertSee('Logout');
        $response->assertSee('User: <b>'.$user->name.'</b>');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }

    // доступ к странице тегов admin пользователя
    /** @test */
    public function tagPageAdminUser()
    {
        $user = factory(App\User::class)->create();
        $user->setAdmin();
        $response = $this->actingAs($user)->get('/tags');
        $response->assertStatus(200);
        $response->assertSee('Tags');
        $response->assertSee('Add a new tag');
    }

    // открытие страницы редактирования тега admin пользователем
    /** @test */
    public function openTagEditAdminUser()
    {
        // нам нужен пользователь
        $user = factory(App\User::class)->create();
        $user->setAdmin();
        // нужен тег
        $tag = factory(App\Tag::class)->create();

        $response = $this->actingAs($user)->get('/edittag/'.$tag->id);
        $response->assertStatus(200);
        $response->assertSee('Edit Tag');
        $response->assertSee('Tag');
        $response->assertSee($tag->tag);
    }

    // создание тега admin пользователем
    /** @test */
    public function addTagAdminUser()
    {
        $user = factory(App\User::class)->create();
        $user->setAdmin();
        $data = [
            'tag' => 'My tag'
        ];
        $response = $this->actingAs($user)->post('/storetag', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/tags');
    }

    // апдейтим тег admin пользователем
    /** @test */
    public function updateTagAdminUser()
    {
        // нам нужен пользователь
        $user = factory(App\User::class)->create();
        $user->setAdmin();
        // нужен тег
        $tag = factory(App\Tag::class)->create();

        $data = [
            'id' => $tag->id,
            'tag' => 'My updated tag'
        ];
        $response = $this->actingAs($user)->post('/updatetag', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/tags');
    }

    // удаление тега admin пользователем
    /** @test */
    public function deleteTagAdminUser()
    {
        // нам нужен пользователь
        $user = factory(App\User::class)->create();
        $user->setAdmin();
        // нужен тег
        $tag = factory(App\Tag::class)->create();
        $response = $this->actingAs($user)->get('/deletetag/'.$tag->id);
        $response->assertStatus(302);
        $response->assertRedirect('/tags');
    }

    /*    public function testRegisterUser()
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
    }*/

   /* function testUserLogin()
    {
        $user = factory(App\User::class)->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertSee('User: <b>'.$user->name.'</b>');
        $response->assertSee('Logout');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }

    function testUserShowRegisterForm()
    {
        $response = $this->get('/register');
        $response->assertSee('Name');
        $response->assertSee('E-Mail Address');
        $response->assertSee('Password');
        $response->assertSee('Confirm Password');
        $response->assertSee('Register');
    }

    function testUserLogout()
    {
        $user = factory(App\User::class)->create();
        $response = $this->actingAs($user)->get('/logout');
        $response->assertRedirect('/');
        $response->assertDontSee('User:');
    }

    function testUserShowLoginForm()
    {
        $response = $this->get('/login');
        $response->assertSee('Login');
        $response->assertSee('E-Mail Address');
        $response->assertSee('Password');
        $response->assertDontSee('Logout');
    }*/

}
