<?php

\app\assets\AppAsset::register($this);
\app\assets\AlbumsAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php $this->head() ?>
    <?php use yii\helpers\Html; ?>
    <?= Html::csrfMetaTags() ?>
</head>
<body>
    <?php $this->beginBody(); ?>

    <div class="container">
        <?= $this->render('/chunks/top-nav') ?>

        <?= $content ?>
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
