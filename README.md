# Yii2 file manager

ATTENTION: THIS REPOSITORY WAS ARCHIVED!

This module is based on [pendalf89/yii2-filemanager](https://github.com/PendalF89/yii2-filemanager).
This module provide interface to collect and access all mediafiles in one place. Inspired by WordPress file manager.

## Features

* Integrated with TinyMCE editor.
* Automatically create actually directory for uploaded files like "2014/12". Or you can castomize it in module settings.
* Automatically create thumbs for uploaded images
* Unlimited number of sets of miniatures
* All media files are stored in a database that allows you to attach to your object does not link to the image, and the id of the media file. This provides greater flexibility since in the future will be easy to change the size of thumbnails.
* If your change thumbs sizes, your may resize all existing thumbs in settings.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add

```
{
	"type": "git",
	"url": "https://github.com/vommuan/yii2-filemanager"
}
```

to the repositories section of your `composer.json` file.

Then run 

```
php composer.phar require vommuan/yii2-filemanager "@dev"
```

or add 

```
"vommuan/yii2-filemanager": "@dev"
```

to the require section of your `composer.json` file.

Apply migration

```
yii migrate --migrationPath=@vendor/vommuan/yii2-filemanager/migrations/
```

## Configuration

```php
'modules' => [
    'filemanager' => [
        'class' => 'vommuan\filemanager\Module',
        
        // Rename files
        'rename' => false,
        
        // Upload routes
        'routes' => [
            // Base web directory url
			'basePath' => '@webroot',
			
			// Path for uploaded files in web directory
			'uploadPath' => 'uploads',
			
			// Directory format for uploaded files.
			'dateDirFormat' => 'Y/m',
			
			// Thumbs directory template. Path, where thumb files are located
			'thumbsDirTemplate' => '{uploadPath}/{dateDirFormat}',
        ],
        
        // Thumbnails info
        'thumbs' => [
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
        ],
    ],
],
```

### Configuration. Routes

Default values:

```php
'routes' => [
	// Base web directory url
	'basePath' => '@webroot',
	
	// Path for uploaded files in web directory
	'uploadPath' => 'uploads',
	
	// Directory format for uploaded files.
	'dateDirFormat' => 'Y/m',
	
	// Thumbs directory template. Path, where thumb files are located
	'thumbsDirTemplate' => '{uploadPath}/{dateDirFormat}',
],
```

If some of these parameters is not defined, default values will be used.

Default, the path to upload files is `@webroot/uploads/2016/06`, if you upload file in June 2016. Path for thumbnails is similar.

#### basePath

Base web directory url. It used for generate absolute path to web directory. It renders by function `Yii::getAlias()`.

For example:

````php
'basePath' => '@webroot'
````

```php
'basePath' => '@frontend/web'
```

```php
'basePath' => 'system/upload/directory'
```

#### uploadPath

Path for uploaded files in web directory. It renders without changes.

For example:

```php
'uploadPath' => 'uploads'
```

```php
'uploadPath' => 'files/upload'
```

#### dateDirFormat

Directory format for uploaded files. Makes directory recursively. It renders by function `date($dateDirFormat, time())`. More about available parameters [read here](http://php.net/manual/en/function.date.php).

For example:

```php
'dateDirFormat' => 'Y/m'
```

```php
'dateDirFormat' => 'Y/m/d'
```

```php
'dateDirFormat' => 'Y-m'
```

#### thumbsDirTemplate

Thumbs directory template. Path, where thumb files are located.

Parameters `{uploadPath}` and `{dateDirFormat}` are replaced by appropriate values in settings.

For example:

```php
'thumbsDirTemplate' => '{uploadPath}/{dateDirFormat}'
```

```php
'thumbsDirTemplate' => '{uploadPath}/thumbs/{dateDirFormat}'
```

```php
'thumbsDirTemplate' => '{uploadPath}/{dateDirFormat}/thumbs'
```

### Configuration. Thumbs

Thumbnails are resized in "outbound" mode.

Default configuration:

```php
'thumbs' => [
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
],
```

Structure of thumbs configuration:

```php
'thumbs' => [
	'<alias>' => [
		'name' => 'Name of alias',
		'size' => ['<width>', '<height>'],
	],
],
```

Alias `default` is reserved by module for displaying uploaded images. If you set it, it will be rewrite by module default settings.

### Configuration. Other parameters

#### rename

Set true if you want to rename files if the name is already in use. Default value: `false`.

For example:

```php
'rename' => true
```

#### autoUpload

Set true to enable autoupload. Default value: `false`.

```php
'autoUpload' => true
```

## Usage

Simple standalone field:

```php
use vommuan\filemanager\widgets\FileInput;

echo $form->field($model, 'original_thumbnail')->widget(FileInput::className(), [
    'buttonTag' => 'button',
    'buttonName' => 'Browse',
    'buttonOptions' => ['class' => 'btn btn-default'],
    'options' => ['class' => 'form-control'],
    
    // Widget template
    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
    
    // Optional, if set, only this image can be selected by user
    'thumb' => 'original',
    
    // Optional, if set, in container will be inserted selected image
    'imageContainer' => '.img',
    
    // Default to FileInput::DATA_URL. This data will be inserted in input field
    'pasteData' => FileInput::DATA_URL,
    
    // JavaScript function, which will be called before insert file data to input.
    // Argument data contains file data.
    // data example: [alt: "Four cats", description: "123", url: "/uploads/2014/12/cats-100x100.jpeg", id: "45"]
    'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
]);

echo FileInput::widget([
    'name' => 'mediafile',
    'buttonTag' => 'button',
    'buttonName' => 'Browse',
    'buttonOptions' => ['class' => 'btn btn-default'],
    'options' => ['class' => 'form-control'],
    
    // Widget template
    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
    
    // Optional, if set, only this image can be selected by user
    'thumb' => 'original',
    
    // Optional, if set, in container will be inserted selected image
    'imageContainer' => '.img',
    
    // Default to FileInput::DATA_IDL. This data will be inserted in input field
    'pasteData' => FileInput::DATA_ID,
    
    // JavaScript function, which will be called before insert file data to input.
    // Argument data contains file data.
    // data example: [alt: "Four cats", description: "123", url: "/uploads/2014/12/cats-100x100.jpeg", id: "45"]
    'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
]);
```

With TinyMCE:

```php
use vommuan\filemanager\widgets\TinyMCE;

<?= $form->field($model, 'content')->widget(TinyMCE::className(), [
    'clientOptions' => [
           'language' => 'ru',
        'menubar' => false,
        'height' => 500,
        'image_dimensions' => false,
        'plugins' => [
            'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table',
        ],
        'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
    ],
]); ?>
```

In model you must set mediafile behavior like this example:

```php
use vommuan\filemanager\behaviors\MediaFileBehavior;

public function behaviors()
{
    return [
        'mediafile' => [
            'class' => MediaFileBehavior::className(),
            'name' => 'post',
            'attributes' => [
                'thumbnail',
            ],
        ]
    ];
}
```

Than, you may get mediafile from your owner model.
See example:

```php
use vommuan\filemanager\models\MediaFile;

$model = Post::findOne(1);
$mediaFile = MediaFile::loadOneByOwner('post', $model->id, 'thumbnail');

// Ok, we have mediafile object! Let's do something with him:
// return url for small thumbnail, for example: '/uploads/2014/12/flying-cats.jpg'
echo $mediaFile->thumbFiles->getUrl('small');
```
