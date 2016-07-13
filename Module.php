<?php
namespace vommuan\filemanager;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vommuan\filemanager\controllers';

    /**
     * Set true if you want to rename files if the name is already in use 
     * @var boolean 
     */
    public $rename = false;
    
     /**
      * Set true to enable autoupload
      * @var boolean 
      */
    public $autoUpload = false;
    
    /**
     * @var array upload routes
     */
    public $routes = [];
    
    private $_defaultRoutes = [
        /**
         * Base web directory url
         */
        'basePath' => '@webroot',
        
        /**
         * Path for uploaded files in web directory
         */
        'uploadPath' => 'uploads',
        
        /** 
         * Directory format for uploaded files. Default yyyy/mm
         * Read more about avaliable parameters: http://php.net/manual/en/function.date.php
         */
        'dateDirFormat' => 'Y/m',
        
        /**
         * Thumbs directory template. Path, where thumb files are located
         */
        'thumbsDirTemplate' => '{uploadPath}/{dateDirFormat}',
    ];

    /**
     * @var array thumbnails info
     */
    public $thumbs = [
        'small' => [
            'name' => 'Small size',
            'size' => [120, 80],
        ],
        'medium' => [
            'name' => 'Medium size',
            'size' => [400, 300],
        ],
        'large' => [
            'name' => 'Large size',
            'size' => [800, 600],
        ],
    ];
    
    /**
     * @var boolean
     */
    public $thumbsAutoCreate = true;

    /**
     * @var array max image sizes, [width, height]
     */
    public $maxImageSizes;
    
    /**
     * @var boolean ignore image rotate for setting max sizes
     */
    public $ignoreImageRotate = false;
    
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/filemanager/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/vommuan/yii2-filemanager/messages',
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

        return Yii::t("modules/filemanager/$category", $message, $params, $language);
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
