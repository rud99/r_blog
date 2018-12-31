<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /** @test */

    public function register()
    {
        $data = [
            'email' => 'jphn@dmail.com',
            'name' => 'John Do',
            'password' => 'password'
        ];
        User::register($data);

        $this->assertDatabaseHas('users', [
            'email' => 'jphn@dmail.com',
            'name' => 'John Do'
        ]);
    }
}
