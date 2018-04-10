<?php

namespace app\models\forms;

use Yii;
use app\models\PhotoAlbum;
use yii\base\Model;

class PhotoAlbumCreate extends Model {
    public $id;
    public $name;
    public $description;

    public function rules() {
        return [
            ['name', 'required', 'message' => 'Введите название'],
            ['description', 'safe'],
        ];
    }

    public function attributeLabels() {
        /*return [
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description')
        ];*/
        return [
            'name' => 'Name',
            'description' => 'Description'
        ];
    }

    public function create() {
        if ($this->validate()) {
            $photoAlbum = $this->id ? PhotoAlbum::findOne($this->id) : new PhotoAlbum();
            $photoAlbum->name = $this->name;
            //TODO: set user_id
            $photoAlbum->user_id = 1;
            $photoAlbum->description = $this->description;
            if ($photoAlbum->save()) {
                return $photoAlbum->id;
            }
        }

        return null;
    }
}