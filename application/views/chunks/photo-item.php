<?php
use yii\helpers\Html;
?>
<!-- Чанк элемента блока фотографий для раздела "альбомы" -->
<div class="col-lg-4">
    <?= Html::a(
        '<img id="photo_id'.$model->getId()
        .'" src="'.$model->getImageUrl([367, 247])
        .'" alt="Photo 1"'
        .'" data-id="'.$model->getId()
        .'" />',
        false,
        [
            'data-toggle' => 'modal',
            'data-target' => '#show_photo'
        ]
    ) ?>
</div>
