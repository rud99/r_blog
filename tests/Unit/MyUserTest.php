<?php

namespace Tests\Unit;

use App;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyUserTestTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterUser()
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

    function testUserLogin()
    {
        $user = factory(App\User::class)->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertSee('User: <b>'.$user->name.'</b>');
        $response->assertSee('Logout');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
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
    }

}
