<?php

use Faker\Generator as Faker;

$factory->define(\miudl\Usuario\Usuario::class, function (Faker $faker) {
    return [
        'Usuario' => $faker->name.'@miudl',
        'password' => '$2y$10$ntacSivzlcx6aAF9TKG5i.ZxJcRX3ebYlGSjvHUJ/DF6JOXgQZ3lq', // secret
        'remember_token' => Str::random(10),
    ];
});
