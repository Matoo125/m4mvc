<?php 

require_once('src/core/App.php');
require_once('src/core/Controller.php');
require_once('src/core/Model.php');

require_once('src/helper/Str.php');
require_once('src/helper/Query.php');
require_once('src/helper/Redirect.php');
require_once('src/helper/user/UserController.php');


require_once('tmp/controllers/api/Home.php');
require_once('tmp/controllers/api/Test.php');
require_once('tmp/controllers/api/User.php');

require_once('vendor/autoload.php');

require_once('tmp/model/Test.php');

$app = new m4\m4mvc\core\App;


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

