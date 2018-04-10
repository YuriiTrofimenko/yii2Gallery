<?php

namespace app\models;

use Imagine\Gd\Imagine;
use Yii;

class Photo extends \yii\db\ActiveRecord {

    public static function tableName()
    {
        return 'Photo';
    }

    public function behaviors() {
        return [
            'app\behaviors\TimestampBehavior'
        ];
    }

    public function rules() {
        return [
            ['user_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => 'app\models\User'],
            ['file_name', 'required'],
            ['file_name', 'string', 'min' => 32,'max' => 32],
            ['file_name', 'validateImage'],
            ['description', 'string']
        ];
    }

    public function validateImage(){
        if (!$this->hasErrors('file_name') && !empty($this->file_name)){
            if (!file_exists($this->getImagePath())){
                $this->addError('file_name', 'Image not found');
            }
        }
    }

    public function attributeLabels()
    {
        /*return [
            'description' => Yii::t('app.models', 'Описание'),
            'createdAt' => Yii::t('app.models', 'Дата создания'),
        ];*/
        return [
            'description' => 'Описание',
            'createdAt' => 'Дата создания',
        ];
    }

    public static function findIdentity($id)
    {
        return Photo::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function findByUserId($user_id) {
        if (!empty($user_id)) {
            return \yii\helpers\ArrayHelper::getColumn(
                self::find()
                    ->where(['user_id' => $user_id])
                    ->indexBy('createdAt')
                    ->all(),
                'file_name'
            );
        }
    }

    public static function getImageSizes(){
        //180, 360
        return [[245, 165], [367, 247]];
    }

    public function getImagePath($size = null){
        if ($size){
            return Yii::getAlias("@webroot/images/{$this->file_name}_{$size[0]}_{$size[1]}.png");
        } else {
            return Yii::getAlias("@webroot/images/{$this->file_name}.png");
        }
    }

    public function getImageUrl($size = null){
        if ($size){
            return Yii::getAlias("@web/images/{$this->file_name}_{$size[0]}_{$size[1]}.png");
        } else {
            return Yii::getAlias("@web/images/{$this->file_name}.png");
        }
    }

    public function uploadImage($uploadedImage){
        if ($uploadedImage) {
            //$this->deleteImage();

            $this->file_name = Yii::$app->security->generateRandomString(32);
            $image = \yii\imagine\Image::getImagine()->open($uploadedImage);
            $image->save($this->getImagePath());

            foreach($this->getImageSizes() as $size) {
                $thumbnail = $image->thumbnail(new \Imagine\Image\Box($size[0], $size[1]), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                $thumbnail->save($this->getImagePath($size));
            }

            if ($this->save()) {
                return ['image_url' => $this->getImageUrl([245, 165])];
            }
            else
            {
                return $this->getErrors();
            }
        }
    }

    public function saveDescription($description){
        if ($description !== null) {
            $this->description = $description;
            if (!$this->save(true, ['description'])) {
                return $this->getErrors();
            }
            return "saving is ok";
        }
        return "nothing to save";
    }

    public function deletePhoto(){
        if ($this->delete()) {
            if(!unlink($this->getImagePath())){
                return 'Failed to delete file: '.$this->getImagePath();
            }
            foreach($this->getImageSizes() as $size) {
                if(!unlink($this->getImagePath($size))){
                    return 'Failed to delete file: '.$this->getImagePath($size);
                }
            }

            $newCoverPhoto = Photo::find()
                ->where(['album_id' => $this->album_id])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            $newCoverId = $newCoverPhoto->id;
            $album = PhotoAlbum::findOne($this->album_id);
            $album->saveCoverId($newCoverId);
            $newCoverFileName = $newCoverPhoto->file_name;
            //$newCoverFileNameResult = Photo::findOne($newCoverId);
            //$newCoverFileName = $newCoverFileNameResult ? $newCoverFileNameResult->select('id') : '';
        }else{
            return $this->getErrors();
        }
        return [
            'new_cover_file_name' => ($newCoverFileName)
                ? Yii::getAlias("@web/images/{$newCoverFileName}_{$size[0]}_{$size[1]}.png")
                : Yii::getAlias("@web/images/system/albums_no_photo.png"),
            'album_id' => $this->album_id
        ];

        /*
        new DeletePhotoResponse(
            ($newCoverFileName)
                ? Yii::getAlias("@web/images/{$newCoverFileName}_{$size[0]}_{$size[1]}.png")
                : Yii::getAlias("@web/images/system/albums_no_photo.png"),
            $this->album_id
        );
         * */
    }
}