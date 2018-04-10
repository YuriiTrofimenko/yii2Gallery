<?php

namespace app\models\forms;

use Yii;
use app\models\User;


class UserRegistration extends \yii\base\Model {
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $confirm;

    public function rules() {
        return [
            ['firstname', 'required', 'message' => Yii::t('app', 'Введите имя')],
            ['lastname', 'required', 'message' => Yii::t('app', 'Введите фамилию')],
            ['email', 'required', 'message' => Yii::t('app', 'Введите email')],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => Yii::t('app', 'Такой email уже зарегистрирован')],
            ['password', 'required', 'message' => Yii::t('app', 'Введите пароль')],
            ['confirm', 'required', 'message' => Yii::t('app', 'Подтвердите пароль')],
            ['password', 'compare', 'compareAttribute' => 'confirm', 'message' => Yii::t('app', 'Пароли не совпадают')],
            ['confirm', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Пароли не совпадают')]
        ];
    }

    public function attributeLabels()
    {
        return [
            'firstname' => Yii::t('app', 'Имя'),
            'lastname' => Yii::t('app', 'Фамилия'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Пароль'),
            'confirm' => Yii::t('app', 'Подтверждение пароля'),
        ];
    }

    public function registration() {
        if ($this->validate()) {
            $user = new User([
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'password' => Yii::$app->security->generatePasswordHash($this->password)
            ]);

            if ($user->save()) {
                Yii::$app->user->login($user);
                return $user;
            }

            $this->addError('', Yii::t('app', 'Ошибка создания пользователя'));
        }

        return null;
    }
}