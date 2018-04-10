<?php

namespace app\models;

use Yii;

class PhotoAlbum extends \yii\db\ActiveRecord {

    public static function tableName()
    {
        return 'PhotoAlbum';
    }

    public function behaviors() {
        return [
            'app\behaviors\TimestampBehavior'
        ];
    }

    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'string'],
            ['user_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => 'app\models\User'],
            //['cover_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => 'app\models\Photo'],
            ['description', 'string']
        ];
    }

    public function attributeLabels()
    {
        /*return [
            'name' => Yii::t('app.models', 'Альбом'),
            'createdAt' => Yii::t('app.models', 'Дата создания'),
        ];*/

        return [
            'name' => 'Альбом',
            'createdAt' => 'Дата создания',
        ];
    }

    public static function findIdentity($id)
    {
        return PhotoAlbum::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findByName($name) {
        return self::find()->where(['name' => $name])->one();
    }

    public function getCoverUrl($size = null){
        $photo = Photo::findIdentity($this->cover_id);
        if ($size){
            return $photo
                ? Yii::getAlias("@web/images/{$photo->file_name}_{$size[0]}_{$size[1]}.png")
                : Yii::getAlias("@web/images/system/albums_no_photo.png");
        } else {
            return $photo
            ? Yii::getAlias("@web/images/{$photo->file_name}.png")
            : Yii::getAlias("@web/images/system/albums_no_photo.png");
        }
    }

    public function saveCoverId($cover_id){
        $this->cover_id = $cover_id;
        if (!$this->save(true, ['cover_id'])) {
            return $this->getErrors();
        }
        return "saving is ok";
    }
}