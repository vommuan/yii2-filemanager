<?php

namespace vommuan\filemanager;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vommuan\filemanager\controllers';

    /**
     *  Set true if you want to rename files if the name is already in use 
     * @var boolean 
     */
    public $rename = false;
    
     /**
     *  Set true to enable autoupload
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
    
    private $_defaultThumbs = [
        'default' => [
            'name' => 'Default size',
            'size' => [128, 128],
        ],
    ];

    /**
     * @var array default thumbnail size, using in filemanager view.
     */
    private static $defaultThumbSize = [128, 128];

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

    /**
     * @return array default thumbnail size. Using in filemanager view.
     */
    public static function getDefaultThumbSize()
    {
        return self::$defaultThumbSize;
    }
    
    public function getDefaultRoutes()
    {
        return $this->_defaultRoutes;
    }
    
    public function getDefaultThumbs()
    {
        return $this->_defaultThumbs;
    }
}
