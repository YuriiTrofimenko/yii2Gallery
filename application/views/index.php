<?php
/**
 * @var app\models\forms\UserLogin $loginModel
 * @var app\models\forms\UserRegistration $registrationModel
 */

use app\widgets\Modal;
?>

<?php Modal::begin([
    'closeButton' => false,
    'header' => null,
    'size' => Modal::SIZE_SMALL,
    'clientOptions' => [
        'show' => true,
        'backdrop' => true
    ]
]) ?>

    <?= \yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Вход'),
                'content' => $this->render('/chunks/login-form', ['model' => $loginModel])
            ],
            [
                'label' => Yii::t('app', 'Регистрация'),
                'content' => $this->render('/chunks/registration-form', ['model' => $registrationModel])
            ]
        ]
    ]) ?>

<?php Modal::end() ?>
