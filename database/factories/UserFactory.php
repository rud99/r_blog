<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'image' => 'uploads/naFvD6GqaSxuA85dFHPILViq45StihxmVilgoXzd.jpeg',
        'text' => $faker->text,
        'user_id' => factory(App\User::class)->create()->id,
        'views' =>  rand(10, 1000)
    ];
});

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'post_id' => factory(App\Post::class)->create()->id,
        'user_id' => factory(App\User::class)->create()->id,
        'comment' => $faker->text
    ];
});

$factory->define(App\Tag::class, function (Faker $faker) {
    return [
        'tag' => $faker->text(5)
    ];
});