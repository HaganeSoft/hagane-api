<?php
return [
	'appPath' => getcwd().'/../',
	'db_engine' => getenv('db_engine'),
	'db_server' => getenv('db_server'),
	'db_database' => getenv('db_database'),
	'db_user' => getenv('db_user'),
	'db_password' => getenv('db_password'),
	'session_time' => getenv('session_time')
];
?>
