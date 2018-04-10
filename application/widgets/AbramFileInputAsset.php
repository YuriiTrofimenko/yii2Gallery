<?php

namespace app\widgets;

class AbramFileInputAsset extends \kartik\file\FileInputAsset {
    public function init()
    {
        $this->setSourcePath('@app/widgets/assets/abram_file_input');
        $this->setupAssets('css', ['css/fileinput']);
        $this->setupAssets('js', ['js/fileinput']);
        parent::init();
    }
}