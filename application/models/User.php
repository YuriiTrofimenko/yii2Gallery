<?php

namespace app\models;

use Yii;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {
    public static function tableName()
    {
        return 'User';
    }

    public function behaviors() {
        return [
            'app\behaviors\TimestampBehavior'
        ];
    }

    public function rules() {
        return [
            [['password', 'email', 'firstname', 'lastname'], 'required'],
            ['password', 'string', 'min' => 60, 'max' => 60],
            [['email', 'firstname', 'lastname'], 'string', 'max' => 100],
            ['email', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app.models', 'Email'),
            'password' => Yii::t('app.models', 'Пароль'),
            'firstname' => Yii::t('app', 'Имя'),
            'lastname' => Yii::t('app', 'Фамилия'),
            'birth' => Yii::t('app', 'Дата рождения'),
            'gender' => Yii::t('app', 'Пол'),
            'maritalStatus' => Yii::t('app', 'Семейное положение'),
            'createdAt' => Yii::t('app.models', 'Дата создания'),
            'updatedAt' => Yii::t('app.models', 'Дата обновления'),
        ];
    }

    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public static function findByEmail($email) {
        return self::find()->where(['email' => $email])->one();
    }

    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getFullname() {
        return $this->firstname . ' ' . $this->lastname;
    }
}