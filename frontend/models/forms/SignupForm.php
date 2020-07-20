<?php

namespace frontend\models\forms;

use frontend\models\Users;
use frontend\traits\Rules;
use yii\base\Model;

class SignupForm extends Model
{
    use Rules;

    public string $email     = '';
    public string $user_name = '';
    public string $password  = '';
    public int    $city_id   = 1;

    public function rules()
    {
        return array_merge(
            static::usernameRules(),
            static::emailRules(),
            static::passwordRules(),
            [
                ['email', 'unique', 'targetClass' => Users::class, 'message' => "{attribute} {value} уже используется"],
                [
                    ['email', 'user_name', 'password', 'city_id'],
                    'required',
                    'message' => 'Поле обязательно для заполнения'
                ],
            ]);
    }

    public function attributeLabels()
    {
        return [
            'email'     => 'email',
            'user_name' => 'user name',
            'city_id'   => 'city ID',
            'password'  => 'password'
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
