<?php

use Faker\Generator;
use SoapBox\SerializedPayloads\Payload;

$factory->define(Payload::class, function (Generator $faker) {
    return [
        'data' => '',
    ];
});

$factory->state(Payload::class, 'unprocessed', function (Generator $faker) {
    return [
        'processed_at' => null,
    ];
});

$factory->state(Payload::class, 'recently-processed', function (Generator $faker) {
    return [
        'processed_at' => now(),
    ];
});

$factory->state(Payload::class, 'should-delete', function (Generator $faker) {
    return [
        'processed_at' => now()->subDay()->subSecond(),
    ];
});
