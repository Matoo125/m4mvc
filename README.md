## M4 Framework MVC

This project is in active development. 

Read the [documentation](https://matoo125.github.io/m4mvc/)
Check the [changelog](https://github.com/Matoo125/m4mvc/blob/master/changelog.md)


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

Or check [M4 MVC Example App](https://github.com/Matoo125/M4MVC-Example-App).


Everything important lives in `/src` directory. 



### Apps running on M4MVC
- [Youtube-Knowledgebase](https://github.com/Matoo125/Youtube-Knowledgebase)
- [Codelearn](https://github.com/Matoo125/m4codelearn)
- [M4CMS](https://github.com/Matoo125/M4CMS)