<?php

namespace frontend\models\forms;

use frontend\models\Users;
use yii\base\Model;

class LoginForm extends Model
{
    const WRONG_MESSAGE_EMAIL_AND_PASSWORD = 'Неправильный email или пароль';

    public string $email        = '';
    public string $password     = '';

    private $user;

    public function rules()
    {
        return array_merge(
          Users::emailRules(),
          Users::passwordRules(), [
          [['email', 'password'], 'required'],
          ['password', 'validatePassword'],
        ]);
    }

    public function attributeLabels()
    {
        return [
          'email'    => 'email',
          'password' => 'Пароль',
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, static::WRONG_MESSAGE_EMAIL_AND_PASSWORD);
            }
        }
    }

    public function getUser()
    {
        if ($this->user === null) {
            $this->user = $this->user = Users::findOne(['email' => $this->email]);
        }

        return $this->user;
    }
}
