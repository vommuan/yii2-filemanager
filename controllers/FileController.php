<?php
namespace vommuan\filemanager\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\MediaFile;
use vommuan\filemanager\models\MediaFileFactory;
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
        return $this->render('index');
    }

    public function actionFilemanager()
    {
        $this->layout = 'main';
        
		$model = new MediaFileSearch();

        return $this->render('filemanager', [
			'model' => $model,
            'dataProvider' => $model->search(Yii::$app->request->queryParams),
        ]);
    }

    public function actionUploadmanager()
    {
        $this->layout = 'main';
        
        return $this->render('uploadmanager', [
            'model' => new UploadFileForm(),
        ]);
    }

    /**
     * Provides upload file
     * @return mixed
     */
    public function actionUpload()
    {
        $model = new UploadFileForm();
        
        $handler = $model->getHandler();
        
        $handler->save();
        
        $bundle = FilemanagerAsset::register($this->view);
        
        $response['files'][] = [
            'url'           => $handler->getUrl(),
            'thumbnailUrl'  => $handler->getIcon($bundle->baseUrl),
            'name'          => $handler->getFileName(),
            'type'          => $handler->getType(),
            'size'          => $handler->getSize(),
            'deleteUrl'     => Url::to(['file/delete', 'id' => $handler->id]),
            'deleteType'    => 'POST',
        ];
        
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
        $model = new UpdateFileForm([
			'mediaFile' => MediaFileFactory::getOne($id)
        ]);
        
        $message = Module::t('main', 'Changes not saved.');

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            $message = Module::t('main', 'Changes saved!');
        }

        Yii::$app->session->setFlash('mediafileUpdateResult', $message);

        Yii::$app->assetManager->bundles = false;
        return $this->renderAjax('info', [
            'model' => $model,
            'strictThumb' => null,
        ]);
    }

    /**
     * Delete model with files
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = MediaFileFactory::getOne($id);

        if ($model instanceof ImageFile) {
            $model->thumbFiles->delete();
        }

        $model->deleteFile();
        $model->delete();

        return ['success' => 'true'];
    }

    /**
     * Resize all thumbnails
     */
    public function actionResize()
    {
        $models = ImageFile::findAll();

        foreach ($models as $model) {
            if ($model instanceof ImageFile) {
                $model->thumbFiles->delete();
                $model->thumbFiles->create();
            }
        }

        Yii::$app->session->setFlash('successResize');
        $this->redirect(Url::to(['default/settings']));
    }

    /** 
     * Render model info
     * 
     * @param int $id
     * @param string $strictThumb only this thumb will be selected
     * @return string
     */
    public function actionInfo($id, $strictThumb = null)
    {
        $model = new UpdateFileForm([
			'mediaFile' => MediaFileFactory::getOne($id)
        ]);
        
        Yii::$app->assetManager->bundles = false;
        return $this->renderAjax('info', [
            'model' => $model,
            'strictThumb' => $strictThumb,
        ]);
    }
}
