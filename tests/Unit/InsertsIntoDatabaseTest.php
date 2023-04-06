<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Telepath\Laravel\TelegramUser\Models\User;

uses(RefreshDatabase::class);

it('inserts user into database', function () {
    $data = sign(authData());

    expect(User::count())->toBe(0);

    Auth::validate($data);

    expect(User::count())->toBe(1);

    $user = User::first();
    expect($user)->toMatchArray([
        'id'                       => $data['id'],
        'first_name'               => $data['first_name'],
        'last_name'                => $data['last_name'],
        'username'                 => $data['username'],
        'photo_url'                => $data['photo_url'],
        // other data
        'is_bot'                   => null,
        'language_code'            => null,
        'is_premium'               => null,
        'added_to_attachment_menu' => null,
    ]);
});