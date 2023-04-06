<?php

namespace Telepath\Laravel\TelegramUser\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{

    use \Illuminate\Auth\Authenticatable;

    protected $table = 'telegram_users';

    protected $fillable = [
        'id',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'is_premium',
        'added_to_attachment_menu',
        'photo_url',
    ];

    protected $casts = [
        'is_bot'                   => 'boolean',
        'is_premium'               => 'boolean',
        'added_to_attachment_menu' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return null;
    }

}