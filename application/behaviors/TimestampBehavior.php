<?php

namespace app\behaviors;

class TimestampBehavior extends \yii\behaviors\TimestampBehavior {
    public $createdAtAttribute = 'createdAt';
    public $updatedAtAttribute = 'updatedAt';

    public function init() {
        $this->value = new \yii\db\Expression('NOW()');
        parent::init();
    }
}