<?php

namespace Telepath\Laravel\TelegramUser;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Telepath\Laravel\TelegramUser\Extensions\TelegramUserProvider;

class LaravelUserServiceProvider extends ServiceProvider
{

    public function register(): void {}

    public function boot(): void
    {
        Auth::provider('telegram', function (Application $app, array $config) {
            return new TelegramUserProvider(
                $config['model'],
                $config['bot'] ?? 'main',
                $config['expire'] ?? 24 * 60 * 60,
            );
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

}
