<?php

namespace Telepath\Laravel\TelegramUser\Tests;

use Telepath\Laravel\TelegramUser\LaravelUserServiceProvider;
use Telepath\Laravel\TelegramUser\User;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            LaravelUserServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('auth.providers.users.driver', 'telegram');
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('auth.providers.users.bot', 'main');
        $app['config']->set('auth.providers.users.expire', 24 * 60 * 60);
    }

}