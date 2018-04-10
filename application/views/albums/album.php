<?php
/**
 * @var app\models\PhotoAlbum $album
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

<?php Modal::begin([
    'options' => [
        'id' => 'show_photo'
    ],
    'size' => Modal::SIZE_DEFAULT,
    'header' => ''
]) ?>

    <div id="single_img_container" class="row"></div>

<?php Modal::end() ?>

<!-- Блок добавления новых картинок -->
<div class="row photos title">
    <div class="col-lg-12">
        <h2 id="photos_title" class="pull-left"><?= $album->name ?></h2>
        <?php
            echo FileInput::widget([
                'name' => 'file_name',
                'pluginOptions' => [
                    'uploadUrl' => \yii\helpers\Url::to(['albums/upload-photo', 'album_id' => $album->id]),
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

<!-- Блок вывода всех картинок -->
<?php Pjax::begin(['options' => ['timeout' => 6000, 'data-pjax-timeout' => 6000]]); ?>
    <?= Html::a(
        '',
        \yii\helpers\Url::to(['albums/album', 'id' => $album->id]),
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