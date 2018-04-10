<?php
namespace app\assets;

use yii\web\AssetBundle;

class AlbumsAsset extends AssetBundle {

    public $sourcePath = '@app/views/albums/assets';
    public $baseUrl = '@web';
    public $js = [
        'js/albums.js',
    ];
    public $css = [
        'css/albums.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}