<?php

namespace Telepath\Laravel\TelegramUser;

use Telepath\Laravel\TelegramUser\Exceptions\AuthorizationValidationException;

/**
 * Checks if the provided hash inside the received data is valid.
 * This function does not verify the auth_date field.
 *
 * @throws AuthorizationValidationException
 */
function telegram_verify_data(array $receivedData, string $botToken): bool
{
    if (! isset($receivedData['hash'])) {
        throw new AuthorizationValidationException('hash is missing from received data');
    }

    $checkStr = collect($receivedData)->except(['hash'])
        ->sortKeys()
        ->map(fn($value, $key) => "$key=$value")
        ->join("\n");

    $key = hash('sha265', $botToken, true);
    $hash = hash_hmac('sha256', $checkStr, $key);

    return hash_equals($hash, $receivedData['hash']);
}