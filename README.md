## M4 Framework MVC

This project is in active development. Feel free to contribute or ask me if you have question.

Read the [documentation](https://matoo125.github.io/m4mvc/)


To install it you can do:
```
composer require m4/m4mvc
```

Or dev version
```
composer require m4/m4mvc=dev-master
```

Then:

```php
<?php

use m4\m4mvc\core\App;
use m4\m4mvc\core\Module;

require_once('vendor/autoload.php');

$app = new App;
$app->settings = [
	'namespace'	=>	'your\\app\\namespace',
  'modules'   =>  true // if you want co use modules
];

// register modules
Module::register(['web', 'admin']);


$app->paths = [
  'controllers' =>  'app/controllers',
  'app' =>  'app',
  'theme'       =>  [
    'web'   =>  'app/theme/web', // path to public theme
    'admin' =>  'app/theme/admin' // path to admin theme
  ]
];

// db connection
$app->db([
  'DB_HOST'   =>  'localhost',
  'DB_PASSWORD' =>  '',
  'DB_NAME'   =>  'test',
  'DB_USER'   =>  'root'
]);

// run the app
$app->run();


```

Or check [M4 MVC Example App](https://github.com/Matoo125/M4MVC-Example-App).


Everything important lives in `/src` directory. 


### Apps running on M4MVC
- [Youtube-Knowledgebase](https://github.com/Matoo125/Youtube-Knowledgebase)
- [Codelearn](https://github.com/Matoo125/m4codelearn)
- [M4CMS](https://github.com/Matoo125/M4CMS)
- [Vegapo](https://vegapo.sk)