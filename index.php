<?php 

require_once('src/core/App.php');
require_once('src/core/Controller.php');

require_once('tmp/controllers/api/Home.php');

$app = new m4\m4mvc\core\App;

define('ROOT', dirname(__FILE__));

$app->settings = [
	'namespace'	=>	'tmp'
];

$app->paths = [
	'controllers'	=>	ROOT . '/tmp/controllers'
];

$app->run();