<?php

use yii\db\Migration;

use yii\db\Query;
use yii\helpers\ArrayHelper;

class m160711_224510_add_thumbs_table extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('{{%filemanager_thumbnail}}', [
			'id' => $this->primaryKey(),
			'alias' => $this->string(100)->notNull(),
			'url' => $this->text(4096)->notNull(),
			'mediafile_id' => $this->integer()->notNull(),
			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer(),
		], $tableOptions);

		$this->addForeignKey('fk_mediafile_id', '{{%filemanager_thumbnail}}', 'mediafile_id', '{{%filemanager_mediafile}}', 'id', 'CASCADE', 'CASCADE');
		
		// thumbnail's converter
		$mediaFiles = (new Query())
			->from('{{%filemanager_mediafile}}')
			->all();
		
		for ($i = 0; $i < count($mediaFiles); $i++) {
			$thumbs = unserialize($mediaFiles[$i]['thumbs']);
			foreach ($thumbs as $alias => $url) {
				$this->insert('{{%filemanager_thumbnail}}', [
					'alias' => $alias,
					'url' => $url,
					'mediafile_id' => $mediaFiles[$i]['id'],
					'created_at' => time(),
				]);
			}
		}
		
		$this->dropColumn('{{%filemanager_mediafile}}', 'thumbs');
	}

	public function safeDown()
	{
		$this->addColumn('{{%filemanager_mediafile}}', 'thumbs', $this->text());
		
		// thumbnail's converter
		$mediaFiles = (new Query())
			->from('{{%filemanager_mediafile}}')
			->all();
		
		for ($i = 0; $i < count($mediaFiles); $i++) {
			$thumbs = (new Query())
				->from('{{%filemanager_thumbnail}}')
				->where(['mediafile_id' => $mediaFiles[$i]['id']])
				->all();
			
			$this->update(
				'{{%filemanager_mediafile}}', [
					'thumbs' => serialize(ArrayHelper::map($thumbs, 'alias', 'url')),
				], [
					'id' => $mediaFiles[$i]['id'],
				]
			);
		}
		
		$this->dropTable('{{%filemanager_thumbnail}}');
	}
}
