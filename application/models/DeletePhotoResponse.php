<?php

namespace app\models;

class DeletePhotoResponse {

    public $newCoverFileName;
    public $albumId;

    public function __construct($_newCoverFileName, $_albumId) {
        $this->newCoverFileName = $_newCoverFileName;
        $this->albumId = $_albumId;
    }
}