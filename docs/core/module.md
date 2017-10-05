`m4\m4mvc\core\Module`

[Module](https://github.com/Matoo125/M4CMS/blob/master/app/core/Module.php) 

### Parameters

| Name   | Type   | Description                 |
| ------ | ------ | --------------------------- |
| list   | array  | list of modules             |
| active | string | active module               |

#### Usage

```php
Module::register([
  'web' => [
    'render'  =>  'view',
    'folder'  =>  'theme/public'
  ],
  'admin' => [
    'render'  =>  'view',
    'folder'  =>  'theme/admin',
    'beforeStart' =>  function () {
      if (!isset($_SESSION['user_id'])) {
        echo 'please log in'; exit;
      }
    }
  ],
  'api' =>  [
    'render'  =>  'json'
  ]
]);

