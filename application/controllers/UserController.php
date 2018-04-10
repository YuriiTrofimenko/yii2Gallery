<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\forms\UserLogin;
use app\models\forms\UserRegistration;

class UserController extends \yii\web\Controller {
    public function actionAuth() {
        $loginModel = new UserLogin();
        $registrationModel = new UserRegistration();
        $request = Yii::$app->request;

        if ($request->isPost) {
            switch ($request->post('form')) {
                case 'login' : {
                    $loginModel->load($request->post());
                    if ($loginModel->login()) {
                        return $this->redirect(['user/profile']);
                    }
                    break;
                }

                case 'registration' : {
                    $registrationModel->load($request->post());
                    if ($user = $registrationModel->registration()) {
                        return $this->redirect(['user/profile']);
                    }
                    break;
                }
            }
        }

        return $this->render('/index', [
            'loginModel' => $loginModel,
            'registrationModel' => $registrationModel
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect(['user/auth']);
    }

    public function actionProfile($id = null) {
        if (!$id && Yii::$app->user->isGuest) {
            return $this->redirect(['user/auth']);
        }

        $user = $id ? User::findOne($id) : Yii::$app->user->identity;

        return $this->render('profile', [
            'user' => $user,
            'isOwner' => empty($id)
        ]);
    }

    public function actionMail() {
        Yii::$app->mailer->compose()
            ->setFrom('abram-world@site.com')
            ->setTo(Yii::$app->user->identity->email)
            ->setSubject('Проверка')
            ->setTextBody('Проверка')
            ->send();
    }
}