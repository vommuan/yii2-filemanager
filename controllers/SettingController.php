<?php

namespace vommuan\filemanager\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\MediaFile;

class SettingController extends Controller
{
    public function actionIndex()
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageSettings'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
		
        return $this->render('index');
    }
    
    /**
     * Resize all thumbnails
     */
    public function actionResize()
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageSettings'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
        $models = MediaFile::find()->all();

        for ($i = 0; $i < count($models); $i++) {
			$models[$i]->refreshFileVariants();
        }

        Yii::$app->session->setFlash('successResize');
        $this->redirect(['setting/index']);
    }
}
