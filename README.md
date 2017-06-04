## M4 Framework MVC

```
<?php

use m4\m4mvc\core;

require_once('vendor/autoload.php');

$app = new App;
$app->settings = [
	'namespace'	=>	'your\\app\\namespace'
];

$app->paths = [
	'controllers'	=>	'your/path/to/controllers'
];
$app->run();


```