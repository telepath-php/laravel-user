<?php

use Telepath\Laravel\TelegramUser\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function authData(array $defaults = []): array
{
    return array_merge(
        [
            'id'         => fake()->randomNumber(9, true),
            'first_name' => fake()->firstName,
            'last_name'  => fake()->lastName,
            'username'   => fake()->userName,
            'photo_url'  => fake()->imageUrl,
            'auth_date'  => now()->timestamp,
        ], $defaults
    );
}

function sign(array $data): array
{
    unset($data['hash']);

    $dataCheckString = collect($data)
        ->sortKeys()
        ->map(fn($value, $key) => "{$key}={$value}")
        ->join("\n");

    $secretKey = hash('sha256', config('telepath.bots.main.api_token'), true);

    $hash = hash_hmac('sha256', $dataCheckString, $secretKey);

    $data['hash'] = $hash;

    return $data;
}