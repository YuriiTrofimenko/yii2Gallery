<?php
/** @var app\models\forms\UserRegistration $model */
?>

<?php $form = \yii\bootstrap\ActiveForm::begin([

]) ?>
    <input type="hidden" name="form" value="registration" />

    <?= $form->field($model, 'firstname') ?>

    <?= $form->field($model, 'lastname') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'confirm') ?>

    <div class="clearfix">
        <button class="btn btn-primary pull-right" type="submit">Зарегистрироваться</button>
    </div>

<?php \yii\bootstrap\ActiveForm::end() ?>
