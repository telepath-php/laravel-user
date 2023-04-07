<?php

namespace Telepath\Laravel\TelegramUser\Extensions;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use function Telepath\Laravel\TelegramUser\telegram_verify_data;

class TelegramUserProvider implements UserProvider
{

    public function __construct(
        protected string $model,
        protected string $botIdentifier = 'main',
        protected int $expire = 24 * 60 * 60,
    ) {}

    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        return $model->newModelQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        $retrievedModel = $model->newModelQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();

        if (! $retrievedModel) {
            return null;
        }

        $rememberToken = $retrievedModel->getRememberToken();

        return $rememberToken && hash_equals($rememberToken, $token) ? $retrievedModel : null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);

        $timestamps = $user->timestamps;

        $user->timestamps = false;

        $user->save();

        $user->timestamps = $timestamps;
    }

    public function retrieveByCredentials(array $credentials)
    {
        $identifier = $credentials['id'] ?? null;

        if (! $identifier) {
            return null;
        }

        $model = $this->createModel();

        $data = Arr::only($credentials, $model->getFillable());

        return $model->newModelQuery()
            ->firstOrCreate([
                'id' => $identifier,
            ], $data);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (! isset($credentials['hash'])) {
            return false;
        }

        if (! telegram_verify_data($credentials, $this->getBotToken())) {
            return false;
        }

        $authDate = isset($credentials['auth_date'])
            ? Carbon::parse($credentials['auth_date'])
            : null;

        if (! $authDate || $authDate->isBefore(now()->subSeconds($this->expire))) {
            return false;
        }

        return true;
    }

    /**
     * Create a new instance of the model.
     *
     * @return Model|Authenticatable
     */
    public function createModel()
    {
        $class = '\\' . ltrim($this->model, '\\');

        return new $class;
    }

    protected function getBotToken(): ?string
    {
        return config("telepath.bots.{$this->botIdentifier}.api_token");
    }

}