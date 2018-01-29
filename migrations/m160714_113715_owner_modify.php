<?php

use yii\db\Migration;

use yii\db\Query;
use yii\helpers\ArrayHelper;

class m160714_113715_owner_modify extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('{{%filemanager_owner}}', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'mediafile_id' => $this->integer()->notNull(),
			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer(),
		], $tableOptions);

		$this->addForeignKey('fk_owner_mediafile_id', '{{%filemanager_owner}}', 'mediafile_id', '{{%filemanager_mediafile}}', 'id', 'CASCADE', 'CASCADE');
		
		// owners converter
		$oldOwners = (new Query())
			->from('{{%filemanager_owners}}')
			->all();
		
		for ($i = 0; $i < count($oldOwners); $i++) {
			$this->insert('{{%filemanager_owner}}', [
				'user_id' => $oldOwners[$i]['owner_id'],
				'mediafile_id' => $oldOwners[$i]['mediafile_id'],
				'created_at' => time(),
			]);
		}
		
		$this->dropTable('{{%filemanager_owners}}');
	}

	public function safeDown()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
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
		
		// owners converter
		$newOwners = (new Query())
			->from('{{%filemanager_owner}}')
			->all();
		
		for ($i = 0; $i < count($newOwners); $i++) {
			$this->insert('{{%filemanager_owners}}', [
				'owner_id' => $newOwners[$i]['user_id'],
				'mediafile_id' => $newOwners[$i]['mediafile_id'],
				'owner' => '',
				'owner_attribute' => '',
			]);
		}
		
		$this->dropTable('{{%filemanager_owner}}');
	}
}
