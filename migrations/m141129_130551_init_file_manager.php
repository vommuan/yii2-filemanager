<?php

use yii\db\Migration;

class m141129_130551_init_file_manager extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
        
        $this->createTable('{{%filemanager_mediafile}}', [
            'id' => $this->primaryKey(),
            'filename' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'url' => $this->text()->notNull(),
            'alt' => $this->text(),
            'size' => $this->string()->notNull(),
            'description' => $this->text(),
            'thumbs' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        
        $this->createTable('{{%filemanager_owners}}', [
            'mediafile_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'owner' => $this->string()->notNull(),
            'owner_attribute' => $this->string()->notNull(),
        ], $tableOptions);
        
        $this->addPrimaryKey('pk_filemanager_owners', '{{%filemanager_owners}}', [
			'mediafile_id',
			'owner_id',
			'owner',
			'owner_attribute',
        ]);
        
        $this->addForeignKey(
			'fk_filemanager_owners', 
			'{{%filemanager_owners}}',
            'mediafile_id',
            '{{%filemanager_mediafile}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_filemanager_owners', '{{%filemanager_owners}}');
        $this->dropPrimaryKey('pk_filemanager_owners', '{{%filemanager_owners}}');
        
        $this->dropTable('{{%filemanager_mediafile}}');
        $this->dropTable('{{%filemanager_owners}}');
    }
}
