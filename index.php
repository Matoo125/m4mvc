<?php 

require_once('vendor/autoload.php');

require_once('tmp/controllers/api/Home.php');
require_once('tmp/controllers/api/Test.php');
require_once('tmp/controllers/api/User.php');

require_once('tmp/model/Test.php');

$app = new m4\m4mvc\core\App;

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

$app->settings = [
	'namespace'	=>	'tmp'
];

$app->paths = [
	'controllers'	=>	ROOT . '/tmp/controllers'
];

$app->db([
	'DB_HOST'		=>	'localhost',
	'DB_PASSWORD'	=>	'',
	'DB_NAME'		=>	'test',
	'DB_USER'		=>	'root'
]);

$app->run();

