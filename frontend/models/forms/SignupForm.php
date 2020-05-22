<?php

namespace frontend\models\forms;

use frontend\models\Users;
use yii\base\Model;

class SignupForm extends Model
{
    public string $email = '';
    public string $username = '';
    public string $password = '';
    public int $cityID = 0;

    public function rules()
    {
        return [
          [
            ['email', 'username', 'password', 'cityID'],
            'required',
            'message' => 'Поле обязательно для заполнения',
          ],

          [['username', 'email'], 'trim'],
          [
            ['username'],
            'string',
            'min' => 6,
            'max' => 70,
          ],
          [
            'username',
            'match',
            'pattern' => '/^([a-zа-я-]+)\s([a-zа-я-]+)$/sui',
            'message' =>
              'Имя должно соответствовать шаблону Иван Иванов. Нельзя использовать цифры и специальные символы.'
          ],

          [
            'email',
            'unique',
            'targetClass' => 'frontend\models\Users',
            'message' => "{attribute} {value} уже используется"
          ],
          ['email', 'match', 'pattern' => '/^([a-zа-я0-9\.]+@[а-яa-z]+\.[a-zа-я]+)$/sui'],
          [['email'], 'string', 'min' => 3, 'max' => 50],

          [['password'], 'string', 'min' => 8, 'max' => 255],
        ];
    }

    public
    function attributeLabels()
    {
        return [
          'email' => 'email',
          'userName' => 'user name',
          'cityID' => 'city ID',
          'password' => 'password'
        ];
    }

    public
    function register(): bool
    {
        [$firstName, $lastName] = explode(' ', $this->username);

        $user = new Users();
        $user->email = $this->email;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->city_id = $this->cityID;
        $user->role = $user::ROLE_PERFORMER;
        $user->date_joined = date('Y-m-d H:i:s', time());
        $user->last_activity = date('Y-m-d H:i:s', time());
        $user->setPassword($this->password);

        return $this->isUserRegister($user);
    }

    private function isUserRegister(Users $user): bool
    {
        if ($user->save()) {
            \Yii::$app->session->setFlash('success', 'You have successfully registered');
            return true;
        }
        return false;
    }
}
