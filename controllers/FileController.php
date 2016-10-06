<?php
namespace vommuan\filemanager\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
use yii\base\UserException;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\MediaFile;
use vommuan\filemanager\models\MediaFileSearch;
use vommuan\filemanager\models\UploadFileForm;
use vommuan\filemanager\models\UpdateFileForm;
use vommuan\filemanager\assets\FileGalleryAsset;

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
        
        return $this->render('index');
    }
    
    /**
     * Upload file from next page
     */
    public function actionNextPageFile()
    {
		$model = (new MediaFileSearch())->searchLastOnPage(Yii::$app->request->post('page'));
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		if (isset($model)) {
			return [
				'success' => true,
				'html' => $this->renderPartial('next-page-file', [
					'model' => $model,
				]),
			];
		} else {
			return [
				'success' => false,
				'html' => '',
			];
		}
	}
    
    /**
     * Ajax responce for pagination update
     */
    protected function getPagination()
    {
		$dataProvider = (new MediaFileSearch())->search();
        $dataProvider->prepare();
        
        return [
			'pages'       => $dataProvider->pagination->pageCount,
			'files'       => $dataProvider->totalCount,
			'filesOnPage' => MediaFileSearch::PAGE_SIZE,
		];
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
        
        $mediaFile = (new UploadFileForm())->getHandler();
        
        try {
			if (! $mediaFile->save()) {
				throw new UserException(Module::t('main', 'This file already exists.'));
			}
			
			$bundle = FileGalleryAsset::register($this->view);
			
			$response['files'][] = [
				'id'           => $mediaFile->id,
				'thumbnailUrl' => $mediaFile->getIcon($bundle->baseUrl),
				'pagination'   => $this->getPagination(),
			];
		} catch (UserException $e) {
			$response['files'][] = [
				'name'  => Inflector::slug($mediaFile->file->baseName) . '.' . $mediaFile->file->extension,
				'size'  => $mediaFile->file->size,
				'error' => $e->getMessage(),
			];
		} finally {
			Yii::$app->response->format = Response::FORMAT_JSON;
		
			return $response;
		}
    }

    /**
     * Updated mediafile by id
     * 
     * @param $modal
     * @param $id
     * @return array
     */
    public function actionUpdate($modal, $id)
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

        return $this->renderAjax('details', [
            'model' => $model,
            'modal' => $modal,
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
		
        return [
			'success' => 'true',
			'id' => $id,
			'pagination' => $this->getPagination(),
		];
    }

    /** 
     * Render file information
     * 
     * @param boolean $modal
     * @param int $id
     * @return string
     */
    public function actionDetails($modal, $id)
    {
        if (Module::getInstance()->rbac && (!Yii::$app->user->can('filemanagerManageFiles') && !Yii::$app->user->can('filemanagerManageOwnFiles'))) {
			throw new ForbiddenHttpException(Module::t('main', 'Permission denied.'));
		}
        
        $model = new UpdateFileForm([
			'mediaFile' => MediaFile::findOne($id)
        ]);
        
        return $this->renderAjax('details', [
            'model' => $model,
            'modal' => $modal,
        ]);
    }
    
    /**
     * 
     */
    public function actionInsertFilesLoad()
    {
		$filesId = json_decode(ArrayHelper::getValue(Yii::$app->request->post(), 'selectedFiles', '[]'));
		
		return $this->renderAjax('insert-files-load', [
			'mediaFiles' => MediaFile::findAll($filesId),
		]);
	}
}
