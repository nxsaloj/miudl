<?php

use Faker\Generator as Faker;

$factory->define(\miudl\Trabajador\Trabajador::class, function (Faker $faker) {
    return [
        'Codigo'  => $faker->randomKey(),
        'Nombre' => $faker->firstName,
        'Apellidos' => $faker->lastName,
        'FechaNacimiento' => $faker->dateTimeInInterval('-25 years'),
        'PuestoTrabajo_id'  => function(){return factory(\miudl\PuestoTrabajo\PuestoTrabajo::class)->create()->id;},
        'Usuario_id'=>function(){return factory(\miudl\Usuario\Usuario::class)->create()->id;},
    ];
});
