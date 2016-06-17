<?php
namespace vommuan\filemanager\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use vommuan\filemanager\models\MediaFile;

class MediaFileBehavior extends Behavior
{
    /**
     * @var string owner name
     */
    public $name = '';

    /**
     * @var array owner mediafiles attributes names
     */
    public $attributes = [];

    /**
     * @inheritdoc
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'addOwners',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateOwners',
            ActiveRecord::EVENT_BEFORE_DELETE => 'deleteOwners',
        ];
    }

    /**
     * Add owners to mediafile
     */
    public function addOwners()
    {
        foreach ($this->attributes as $attr) {
            if ($mediafile = $this->loadModel($this->owner->$attr)) {
                $mediafile->addOwner($this->owner->primaryKey, $this->name, $attr);
            }
        }
    }

    /**
     * Update owners of mediafile
     */
    public function updateOwners()
    {
        foreach ($this->attributes as $attr) {
            MediaFile::removeOwner($this->owner->primaryKey, $this->name, $attr);

            if ($mediafile = $this->loadModel($this->owner->$attr)) {
                $mediafile->addOwner($this->owner->primaryKey, $this->name, $attr);
            }
        }
    }

    /**
     * Delete owners of mediafile
     */
    public function deleteOwners()
    {
        foreach ($this->attributes as $attr) {
            MediaFile::removeOwner($this->owner->primaryKey, $this->name, $attr);
        }
    }

    /**
     * Load model by id
     * @param int $id
     * @return MediaFile
     */
    private function loadModel($id)
    {
        return MediaFile::findOne($id);
    }
}