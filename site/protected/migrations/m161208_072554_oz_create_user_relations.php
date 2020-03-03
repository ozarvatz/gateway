<?php

class m161208_072554_oz_create_user_relations extends CDbMigration
{
	
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		public function safeUp()
		{
			$this->execute("ALTER TABLE `isrc_user_friend` ADD FOREIGN KEY (`user_id`) REFERENCES `isrc_user`(`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
		}
	}

	public function safeDown()
	{
	}
	
}