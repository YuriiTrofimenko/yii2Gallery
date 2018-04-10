<?php

namespace app\models\forms;

use Yii;
use app\models\User;

class UserLogin extends \yii\base\Model {
    public $email;
    public $password;

    public function rules() {
        return [
            ['email', 'required', 'message' => Yii::t('app', 'Введите email')],
            ['password', 'required', 'message' => Yii::t('app', 'Введите пароль')],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Пароль')
        ];
    }

    public function login() {
        if ($this->validate() && ($user = User::findByEmail($this->email)) && $user->validatePassword($this->password)) {
            Yii::$app->user->login($user);
            return $user;
        }

        if (!$this->hasErrors()) {
            $this->addError('email', Yii::t('app', 'Неверный email или пароль'));
        }

        return null;
    }
}