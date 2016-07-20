<?php
namespace vommuan\filemanager\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\MediaFile;
use vommuan\filemanager\models\MediaFileSearch;
use vommuan\filemanager\models\UploadFileForm;
use vommuan\filemanager\models\UpdateFileForm;
use vommuan\filemanager\assets\FilemanagerAsset;
use yii\helpers\Url;

class FileController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (defined('YII_DEBUG') && YII_DEBUG) {
            Yii::$app->assetManager->forceCopy = true;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageFiles') && !Yii::$app->user->can('filemanagerManageOwnFiles'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
		$model = new MediaFileSearch();
		
        return $this->render('index', [
			'uploadModel' => new UploadFileForm(),
			'dataProvider' => $model->search(),
        ]);
    }

    /**
     * Provides upload file
     * @return mixed
     */
    public function actionUpload()
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageFiles') && !Yii::$app->user->can('filemanagerManageOwnFiles'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
        $model = new UploadFileForm();
        
        $handler = $model->getHandler();
        
        $saved = $handler->save();
        
        $bundle = FilemanagerAsset::register($this->view);
        
        if ($saved) {
			$response['files'][] = [
				'id'          => $handler->id,
				'url'          => $handler->url,
				'thumbnailUrl' => $handler->getIcon($bundle->baseUrl),
				'name'         => $handler->filename,
				'type'         => $handler->type,
				'size'         => $handler->size,
				'deleteUrl'    => Url::to(['file/delete', 'id' => $handler->id]),
				'deleteType'   => 'POST',
			];
		} else {
			$response['files'][] = [
				'name'  => Inflector::slug($handler->file->baseName) . '.' . $handler->file->extension,
				'size'  => $handler->file->size,
				'error' => Module::t('main', 'This file already exists.'),
			];
		}
        
        Yii::$app->response->format = Response::FORMAT_JSON;
		
        return $response;
    }

    /**
     * Updated mediafile by id
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageFiles') && !Yii::$app->user->can('filemanagerManageOwnFiles'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
        $model = new UpdateFileForm([
			'mediaFile' => MediaFile::findOne($id),
        ]);
        
        $message = Module::t('main', 'Changes not saved.');
		
        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            $message = Module::t('main', 'Changes saved!');
        }

        Yii::$app->session->setFlash('mediafileUpdateResult', $message);

        Yii::$app->assetManager->bundles = false;
        return $this->renderAjax('details', [
            'model' => $model,
        ]);
    }

    /**
     * Delete model with files
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageFiles') && !Yii::$app->user->can('filemanagerManageOwnFiles'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
        $model = MediaFile::findOne($id);
		
		$model->delete();
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
        return ['success' => 'true'];
    }

    /** 
     * Render file information
     * 
     * @param int $id
     * @return string
     */
    public function actionDetails($id)
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageFiles') && !Yii::$app->user->can('filemanagerManageOwnFiles'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
        $model = new UpdateFileForm([
			'mediaFile' => MediaFile::findOne($id)
        ]);
        
        Yii::$app->assetManager->bundles = false;
        return $this->renderAjax('details', [
            'model' => $model,
        ]);
    }
}
