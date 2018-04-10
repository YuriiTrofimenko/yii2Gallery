<?php

namespace app\controllers;

use app\models\forms\PhotoAlbumCreate;
use Yii;
use app\models\Photo;
use yii\data\ActiveDataProvider;
use app\models\PhotoAlbum;
use app\models\DeletePhotoResponse;

class AlbumsController extends \yii\web\Controller {

    public function actionIndex($id = null) {
        //
        $photoAlbumCreateModel = new PhotoAlbumCreate();
        //
        if (Yii::$app->request->isPost && !Yii::$app->request->isAjax) {
            $photoAlbumCreateModel->load(Yii::$app->request->post());
            $album_id = $photoAlbumCreateModel->create();
            if ($album_id) {
                return $this->redirect(['albums/album', 'id' => $album_id]);
            }
        }
        //
        $lastPhotos = new ActiveDataProvider([
            'query' => Photo::find()->orderBy('createdAt desc'),
            'pagination' => [
                'pageParam' => 'page-photos',
                'pageSize' => 6,
            ],
        ]);
        //
        $lastAlbums = PhotoAlbum::find()->orderBy('createdAt desc')->limit(8)->all();

        if (Yii::$app->request->isAjax) {
            $allAlbums = PhotoAlbum::find()->orderBy('createdAt desc')->offset(8)->all();
            if (count($allAlbums) == 0) {
                $allAlbums = null;
            }
        } else {
            $allAlbums = null;
        }
        //
        return $this->render('index', [
            'lastAlbums' => $lastAlbums,
            'lastPhotos' => $lastPhotos,
            'allAlbums' => $allAlbums,
            'photoAlbumCreateModel' => $photoAlbumCreateModel
        ]);
    }

    /*public function actionAllAlbums($id = null) {
        //
        if (Yii::$app->request->isAjax) {
            $allAlbums = PhotoAlbum::find()->orderBy('createdAt desc')->offset(8)->all();
            //
            //var_dump(count($allAlbums));
            //die();
            if(count($allAlbums) != 0){
                return $this->render('index', [
                    'allAlbums' => $allAlbums
                ]);
            }else{
                return $this->render('index', [
                    'allAlbums' => null
                ]);
            }
        }
    }*/

    public function actionUploadPhoto($album_id = null){
        if (Yii::$app->request->isAjax) {
            $photo = new Photo([
                'user_id' => 1,
                'album_id' => $album_id ? $album_id : 1,
                'description' => ''
            ]);

            $uploadResult = $photo->uploadImage($_FILES['file_name']['tmp_name']);

            $responseImageUrl = $uploadResult['image_url'];

            /*$responseId = Photo::find()
                ->where(['file_name' => $uploadResult['file_name']])
                ->scalar();*/

            if ($photo->id){
                $album = PhotoAlbum::findOne($album_id ? $album_id : 1);
                $album->saveCoverId($photo->id);
            }

            $response = ['image_url' => $responseImageUrl, 'id' => $photo->id];

            Yii::$app->response->format = 'json';
            return \yii\helpers\BaseJson::encode($response);
        }

        return null;
    }
    //асинхронное сохранение описания картинки в БД
    public function actionSavePhotoDescription(){
        $response = null;
        if (Yii::$app->request->isAjax) {
            $photoId = $_POST['id'];
            $photoValue = $_POST['value'];
            if(isset($photoId) && isset($photoValue)){
                 $photo = Photo::findIdentity((int)$photoId);
                 $response = $photo->saveDescription($photoValue);
            }
        }
        Yii::$app->response->format = 'json';
        return \yii\helpers\BaseJson::encode($response);
    }

    //асинхронное удаление картинки
    public function actionDeletePhoto(){
        $response = null;
        if (Yii::$app->request->isAjax) {
            $photoId = $_POST['id'];
            if(isset($photoId)){
                $photo = Photo::findIdentity((int)$photoId);
                $response = $photo->deletePhoto();
                $response = new DeletePhotoResponse(
                    $response['new_cover_file_name'],
                    $response['album_id']
                );
            }
        }
        //Yii::$app->response->format = 'json';
        return \yii\helpers\BaseJson::encode($response);
    }

    public function actionGetPhoto(){
        $response = null;
        if (Yii::$app->request->isAjax) {
            $photoId = $_POST['id'];
            if(isset($photoId)){
                $response = Photo::findIdentity((int)$photoId);
            }
        }
        Yii::$app->response->format = 'json';
        return \yii\helpers\BaseJson::encode($response);
    }

    //открытие страницы "Альбом"
    public function actionAlbum($id = null) {
        //
        $lastPhotos = new ActiveDataProvider([
            'query' => Photo::find()->where(['album_id' => $id])->orderBy('createdAt desc'),
            'pagination' => [
                'pageSize' => 6,
            ],
        ]);
        //
        $album = PhotoAlbum::findOne($id);
        //
        return $this->render('album', [
            'lastPhotos' => $lastPhotos,
            'album' => $album
        ]);
    }
}