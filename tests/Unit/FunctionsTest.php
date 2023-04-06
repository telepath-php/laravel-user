<?php

it('generates valid authdata', function () {
    $authData = authData();

    expect($authData)->toHaveKeys(['id', 'first_name', 'last_name', 'username', 'photo_url', 'auth_date']);
});

it('signs authdata', function () {
    $authData = authData();

    expect($authData)->not->toHaveKey('hash');

    $signedAuthData = sign($authData);

    expect($signedAuthData)->toHaveKey('hash');
});