<?php

namespace vommuan\filemanager;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'vommuan\filemanager\controllers';
    
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'file';
   
    /**
     * @inheritdoc
     */
    public $layout = 'main';

    /**
     * Set `true` if you want to rename files if the name is already in use 
     * @var boolean 
     */
    public $rename = false;
    
    /**
     * @var array upload routes
     */
    public $routes = [];
    
    private $_defaultRoutes = [
        /**
         * Path for uploaded files
         */
        'uploadPath' => '@webroot/uploads',
        
        /**
         * Symbolic link for uploads path into @webroot directory
         * If 'uploadPath' contains @webroot, symbolic link will not created
         */
        'symLink' => 'uploads',
        
        /** 
         * Directory format for uploaded files. Default yyyy/mm
         * Read more about avaliable parameters: http://php.net/manual/en/function.date.php
         */
        'dateDirFormat' => 'Y/m',
        
        /**
         * Thumbs directory template. Path, where thumb files are located
         */
        'thumbsDirTemplate' => '{dateDirFormat}',
    ];

    /**
     * @var array thumbnails info
     */
    public $thumbs = [];
    
    /**
     * @var boolean
     */
    public $thumbsAutoCreate = true;
    
    /**
     * @var boolean
     */
    public $thumbnailSaveOriginProportions = false;

    /**
     * @var array max image sizes, [width, height]
     */
    public $maxImageSizes;
    
    /**
     * @var boolean ignore image rotate for setting max sizes
     */
    public $ignoreImageRotate = false;
    
    /**
     * @var string user class
     */
    public $userClass = '\common\models\User';
    
    /**
     * @var boolean if `true`, user can manage only his own files
     */
    public $manageOwnFiles = false;
    
    /**
     * @var boolean if `true`, RBAC user manager is enabled
     */
    public $rbac = false;
    
    public function init()
    {
        parent::init();
        Yii::setAlias('@filemanager', __DIR__);
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/filemanager/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@filemanager/messages',
            'fileMap' => [
                'modules/filemanager/main' => 'main.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        if (!isset(Yii::$app->i18n->translations['modules/filemanager/*'])) {
            return $message;
        }

        return Yii::t('modules/filemanager/' . $category, $message, $params, $language);
    }

    public function getDefaultRoutes()
    {
        return $this->_defaultRoutes;
    }
    
    public function getDefaultThumbs()
    {
        return [
			'default' => [
				'name' => Module::t('main', 'Default'),
				'size' => [128, 128],
			],
		];
    }
}
