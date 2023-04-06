<?php

namespace Telepath\Laravel\TelegramUser\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('validates correct data', function (array $data) {
    $result = Auth::validate($data);

    expect($result)->toBeTrue();
})->with('correctAuthData');

it('does not validate expired data', function (array $data) {
    $result = Auth::validate($data);

    expect($result)->toBeFalse();
})->with('expiredAuthData');

it('does not validate wrong hash', function (array $data) {
    $result = Auth::validate($data);

    expect($result)->toBeFalse();
})->with('invalidAuthData');