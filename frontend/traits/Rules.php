<?php

namespace frontend\traits;

trait Rules
{
    public static function emailRules(): array
    {
        return [
            ['email', 'match', 'pattern' => '/^([0-9a-zа-я\.]+@)([а-яa-z]{2,}\.)+([a-zа-я]{2,})+$/sui'],
            [['email'], 'string', 'min' => 3, 'max' => 50],
        ];
    }

    public static function passwordRules(): array
    {
        return [
            [['password'], 'string', 'min' => 8, 'max' => 255],
        ];
    }

    public static function userNameRules(): array
    {
        return [
            [['user_name', 'email'], 'trim'],
            [['user_name'], 'string', 'min' => 6, 'max' => 70],
            [
                'user_name',
                'match',
                'pattern' => '/^([a-zа-я-]+)\s([a-zа-я-]+)$/sui',
                'message' =>
                    'Имя должно соответствовать шаблону Иван Иванов. Нельзя использовать цифры и специальные символы.'
            ],
        ];
    }
}