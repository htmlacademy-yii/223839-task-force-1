<?php

namespace frontend\models\forms;

use frontend\models\Users;
use yii\base\Model;

class SignupForm extends Model
{
    public string $email = '';
    public string $user_name = '';
    public string $password = '';
    public int $city_id = 1;

    public function rules()
    {
        return array_merge(
          static::usernameRules(),
          static::emailRules(),
          [
            [['email', 'user_name', 'password', 'city_id'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['password'], 'string', 'min' => 8, 'max' => 255],
          ]);
    }

    public function attributeLabels()
    {
        return [
          'email' => 'email',
          'user_name' => 'user name',
          'city_id' => 'city ID',
          'password' => 'password'
        ];
    }

    public static function usernameRules(): array
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

    public static function emailRules(): array
    {
        return [
          ['email', 'unique', 'targetClass' => Users::class, 'message' => "{attribute} {value} уже используется"],
          //          ['email', 'email'],
          ['email', 'match', 'pattern' => '/^([0-9a-zа-я\.]+@)([а-яa-z]{2,}\.)+([a-zа-я]{2,})+$/sui'],
          [['email'], 'string', 'min' => 3, 'max' => 50],
        ];
    }

    public function register(): bool
    {
        $user = $this->factoryUser();

        if ($this->validate()) {
            return $user->save();
        }
        return false;
    }

    private function factoryUser(): Users
    {
        [$firstName, $lastName] = explode(' ', $this->user_name . ' ');

        $user = new Users();
        $user->email = $this->email;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->city_id = empty($this->city_id) ? 1 : $this->city_id;
        $user->role = $user::ROLE_PERFORMER;
        $user->date_joined = \Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
        $user->last_activity = \Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
        $user->setPassword($this->password);

        return $user;
    }
}
