<?php
/**
 * @var app\models\PhotoAlbum $photo_album
 * @var app\models\Photo $photo
 * @var app\models\forms\PhotoAlbumCreate $photoAlbumCreateModel
 */

use \yii\helpers\Url;
use \yii\helpers\Html;
use kartik\file\FileInput;
use yii\widgets\Pjax;
use app\widgets\Modal;
use yii\widgets\ListView;
use app\models\Photo;
use app\models\PhotoAlbum;

?>

<!-- Модальное окно создания нового альбома -->
<?php Modal::begin([
    'options' => [
        'id' => 'create_album'
    ],
    'size' => Modal::SIZE_DEFAULT,
    'header' => 'Create album'
]) ?>
    <!-- Контейнер для контролов создания нового альбома -->
    <div id="create_album_container" class="row">
        <?php $form = \yii\bootstrap\ActiveForm::begin([

        ]) ?>
            <div id="create_album_inputs" class="row">
                <div class="col-lg-12">
                    <div class="form-group">
<!--                        <input id="ca_name" class="form-control" type="text" maxlength="64">-->
                        <!--TODO: set maxlength-->
                        <?= $form->field($photoAlbumCreateModel, 'name') ?>
                        <br>
<!--                        <textarea id="ca_description" class="form-control"></textarea>-->
                        <?= $form->field($photoAlbumCreateModel, 'description')->textArea(['rows' => '6']) ?>
                    </div>
                </div>
            </div>
            <div id="create_album_controls" class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary pull-right" type="submit">Create album</button>
                    <a class="btn btn-default pull-right" aria-hidden="true" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        <?php \yii\bootstrap\ActiveForm::end() ?>
    </div>
<?php Modal::end() ?>

<!-- Модальное окно просмотра картинки -->
<?php Modal::begin([
    'options' => [
        'id' => 'show_photo'
    ],
    'size' => Modal::SIZE_DEFAULT,
    'header' => ''
]) ?>
    <!-- Контейнер для вставки картинки и контролов при помощи js -->
    <div id="single_img_container" class="row"></div>
<?php Modal::end() ?>

<!-- Заголовок блока альбомов -->
<div class="row albums title">
    <div class="col-lg-12">
            <h2 class="pull-left">Albums</h2>
            <!-- Создать новый альбом -->
<!--            <a href="--><?//= Url::toRoute('albums/create') ?><!--" class="btn btn-primary pull-right">Create new</a>-->

            <?= Html::a(
                'Create new',
                false,
                [
                    'data-toggle' => 'modal',
                    'data-target' => '#create_album',
                    'class' => 'btn btn-primary pull-right'
                ]
            ) ?>

    </div>
</div>
<!-- альбомы (первые 8) -->
<?php Pjax::begin(['options' => ['timeout' => 6000, 'data-pjax-timeout' => 6000, 'linkSelector'=>'#ajax_last_albums']]); ?>
    <?= Html::a(
        '',
        \yii\helpers\Url::to(['albums/index']),
        [
            'id' => 'ajax_last_albums',
            'hidden' => true
        ]
    ) ?>
    <div class="row albums items">
        <?php foreach ($lastAlbums as $index => $album) { ?>
            <div class="col-lg-3">
                <a href="<?= Url::to(['albums/album', 'id' => $album->getId()]) ?> " data-pjax=0>
                    <img id="album<?=$album->getId()?>" class="img-responsive" src="<?= $album->getCoverUrl([245, 165]) ?>" alt="<?= $album->name ?>">
                </a>
            </div>
        <?php } ?>
    </div>
<?php Pjax::end(); ?>
<!-- показать остальные альбомы (сверх 8) -->
<?php Pjax::begin(['options' => ['timeout' => 6000, 'data-pjax-timeout' => 6000, 'linkSelector'=>'#ajax_all_albums']]); ?>
    <!-- Скрытая ссылка для асинхронного показа и обновления блока  -->
    <div class="row albums more">
        <div class="col-lg-12 text-center">
            <?= Html::a(
                'All albums',
                \yii\helpers\Url::to(['albums/index']),
                [
                    'id' => 'ajax_all_albums',
                    'class' => "btn btn-primary"
                ]
            ) ?>
        </div>
    </div>
    <?php if ($allAlbums != null): ?>
        <div class="row albums items">
            <?php foreach ($allAlbums as $index => $album) { ?>
                <div class="col-lg-3">
                    <a href="<?= Url::to(['albums/album', 'id' => $album->getId()]) ?> " data-pjax=0>
                        <img class="img-responsive" src="<?= $album->getCoverUrl([245, 165]) ?>" alt="<?= $album->name ?>">
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php endif ?>
<?php Pjax::end(); ?>
<!-- Блок добавления новых картинок -->
<div class="row photos title">
    <div class="col-lg-12">
        <h2 id="photos_title" class="pull-left">Photos</h2>
        <?php
            echo FileInput::widget([
                'name' => 'file_name',
                'pluginOptions' => [
                    'uploadUrl' => \yii\helpers\Url::to(['albums/upload-photo']),
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => true,
                    'showPreview' => false,
                    'browseClass' => 'btn btn-primary pull-right',
                    'browseIcon' => '',
                    'browseLabel' =>  'Upload photo',
                    'uploadLabel' => 'Upload',
                    'uploadIcon' => ''
                ],
                'options' => ['accept' => 'image/*', 'multiple' => true]
            ]);
        ?>
    </div>
</div>
<!-- Блок вывода картинок -->
<?php Pjax::begin(['options' => ['timeout' => 6000, 'data-pjax-timeout' => 6000]]); ?>
    <!-- Скрытая ссылка для асинхронного обновления блока картинок после их добавления или удаления -->
    <?= Html::a(
        '',
        \yii\helpers\Url::to(['albums/index']),
        ['id' => 'ajax_photos'],
        ['hidden' => 'true']
    ) ?>
    <div class="row photos items">
        <!-- картинки -->
        <?php echo ListView::widget([
                'dataProvider' => $lastPhotos,
                'itemOptions' => ['class' => 'item'],
                'itemView' => '/chunks/photo-item',
                'summary' => '',
                'emptyText' => 'No photos',
                'pager' => [
                    'class' => \kop\y2sp\ScrollPager::className(),
                    'noneLeftText' => '',
                    'triggerTemplate' => '<div class="ias-trigger" style="text-align: center; cursor: pointer;"><a id="infinity_scroll_link">{text}</a></div>'
                ]
            ]);
        ?>
    </div>
<?php Pjax::end(); ?>