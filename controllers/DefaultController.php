<?php
namespace vommuan\filemanager\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(['file/index']);
    }
}
