<?php

use Faker\Generator as Faker;

$factory->define(\miudl\PuestoTrabajo\PuestoTrabajo::class, function (Faker $faker) {
    return [
        'Codigo'  => $faker->randomKey(),
        'Nombre' => $faker->domainName
    ];
});
