<?php

// This is the database connection configuration.
return array(
	//'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database

	'connectionString' => 'mysql:host=127.0.0.1;dbname=chat',
	'emulatePrepare' => true,
	'username' => 'chat',
	'password' => 'chat',
	'charset' => 'utf8',

);