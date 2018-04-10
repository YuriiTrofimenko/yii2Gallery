<?php
/**
 * @var app\models\User $user
 * @var bool $isOwner
 */
?>

<div class="row profile">
    <div class="col-lg-3">
        <div class="panel panel-default avatar">
            <div class="panel-body">
                <?php if ($isOwner) { ?>
                    <div class="controls"></div>
                <?php } ?>

                <img src="" />
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="panel panel-default general">
            <div class="panel-heading">
                <?= Yii::t('app', 'Основная информация') ?>
            </div>

            <div class="panel-body">
                <?php if ($isOwner) { ?>
                    <div class="controls pull-right">
                        <button class="btn btn-default edit"><?= Yii::t('app', 'Редактировать')?></button>
                        <button class="btn btn-default save hidden"><?= Yii::t('app', 'Сохранить')?></button>
                    </div>
                <?php } ?>

                <ul>
                    <li>
                        <span class="name"><?= Yii::t('app', 'Имя') ?></span>
                        <span class="value"><?= $user->fullname ?></span>
                    </li>

                    <li>
                        <span class="name"><?= Yii::t('app', 'Пол') ?></span>
                        <span class="value"><?= $user->gender ?></span>
                    </li>

                    <li>
                        <span class="name"><?= Yii::t('app', 'Дата рождения') ?></span>
                        <span class="value"><?= $user->birth ?></span>
                    </li>

                    <li>
                        <span class="name"><?= Yii::t('app', 'Семейное положение') ?></span>
                        <span class="value"><?= $user->maritalStatus ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
