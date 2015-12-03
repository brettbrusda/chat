<?php

class m150421_170202_create_user extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_user', array(
            'id' => 'pk',
            'username' => 'string NOT NULL',
            'password' => 'string NOT NULL',
        ), 'ENGINE=InnoDB');
	}

	public function down()
	{
		echo "m150421_170202_create_user does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}