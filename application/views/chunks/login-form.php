<?php
/** @var app\models\forms\UserLogin $model */
?>

<?php $form = \yii\bootstrap\ActiveForm::begin([

]) ?>
    <input type="hidden" name="form" value="login" />

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'password') ?>

    <div class="clearfix">
        <button class="btn btn-primary pull-right" type="submit">Войти</button>
    </div>

<?php \yii\bootstrap\ActiveForm::end() ?>
