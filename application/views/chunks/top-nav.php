<?php
use yii\helpers\Url;
?>

<?php \yii\bootstrap\NavBar::begin([

]) ?>
    <?php if (!Yii::$app->user->isGuest) { ?>
        <?= \yii\bootstrap\Nav::widget([
            'options' => [
                'class' => 'nav navbar-nav pull-right'
            ],
            'items' => [
                [
                    'label' => Yii::t('app', 'Выйти'),
                    'url' => Url::to(['user/logout'])
                ]
            ]
        ]) ?>
    <?php } ?>

<?php \yii\bootstrap\NavBar::end() ?>
