## M4 Framework MVC

This project is in active development. 


To install it you can do:
```
composer require m4\m4mvc=dev-master
```


Then:

```php
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

Or check `index.php` and `/tmp` for better example.