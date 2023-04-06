<?php

dataset('correctAuthData', [
    'correct hash' => fn() => sign(authData()),
]);

dataset('expiredAuthData', [
    'expired data' => fn() => sign(authData([
        'auth_date' => now()->subDay()->timestamp,
    ])),
]);

dataset('invalidAuthData', [
    'wrong hash' => fn() => authData([
        'hash' => 'invalid',
    ]),
]);