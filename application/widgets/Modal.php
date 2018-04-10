<?php

namespace app\widgets;

use yii;
use yii\helpers\Html;

class Modal extends \yii\bootstrap\Modal {
    public function init() {
        ob_start();

        yii\bootstrap\Widget::init();

        $this->initOptions();

        ModalAsset::register($this->view);

        echo $this->renderToggleButton() . "\n";
        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-dialog-wrapper-outer']) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-dialog-wrapper-inner']) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-dialog ' . $this->size]) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-content']) . "\n";
        echo $this->renderHeader() . "\n";
        echo $this->renderBodyBegin() . "\n";
    }

    public function run() {
        echo "\n" . $this->renderBodyEnd();
        echo "\n" . $this->renderFooter();
        echo "\n" . Html::endTag('div'); // modal-content
        echo "\n" . Html::endTag('div'); // modal-dialog
        echo "\n" . Html::endTag('div'); // modal-dialog-wrapper-inner
        echo "\n" . Html::endTag('div'); // modal-dialog-wrapper-outer
        echo "\n" . Html::endTag('div');

        $this->registerPlugin('modal');

        $modalHtml = ob_get_clean();

        Yii::$app->view->on(\yii\web\View::EVENT_END_BODY, function($event) {
            echo $event->data;
        }, $modalHtml);
    }

}