# Controller

`m4\m4mvc\core\Controller`

[This](https://github.com/Matoo125/M4CMS/blob/master/app/core/Controller.php) is [Abstract](http://php.net/manual/en/language.oop5.abstract.php) Controller [Class](http://php.net/manual/en/language.oop5.php) which is intended to be extended by every controller.

You might create your own base controller, which extends this controller.

## Properties

| name           | description                            | note                                     |
| -------------- | -------------------------------------- | ---------------------------------------- |
| model          | to store model object                  |                                          |
| modelNamespace | name space to access model             | This can be set in App->db function      |
| view           | path to view                           | use this if you do not want default module/controller/method path |
| data           | Data to be passed to view or outputted | []                                       |
| response       | Static version of data                 | This is used by Response helper          |

## Usage

All those calls should be made from controller which extends this.

To set model

```php
$this->model = $this->getModel('Name')
// for this to work modelNamespace has to be set
```

Set data

```php
$this->data['key'] = 'value';
```



## Rendering

There are 3 default render functions. render, renderTwig and json.

- json outputs pure json from data + response arrays
- renderTwig renders twig file 
- render is for pure PHP view

you can write your own render function by creating your own base controller and then passing function name to $app->settings['renderFunction']

