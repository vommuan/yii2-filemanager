<?php

namespace vommuan\filemanager\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use vommuan\filemanager\Module;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageFiles') || !Yii::$app->user->can('filemanagerManageSettings'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
		
        return $this->render('index');
    }

    public function actionSettings()
    {
        if (Module::getInstance()->rbac && !Yii::$app->user->can('filemanagerManageSettings')) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
        return $this->render('settings');
    }
}
