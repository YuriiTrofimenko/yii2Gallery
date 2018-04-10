<?php
use yii\helpers\Url;
?>

<div class="col-lg-3">
    <a href="<?= Url::to(['albums/album', 'id' => $model->id]) ?> ">
        <img src="<?= $model->getCoverUrl([245, 165]) ?>" alt="<?= $model->name ?>">
    </a>
</div>
