# App

`m4\m4mvc\core\App`

[App](https://github.com/Matoo125/M4CMS/blob/master/app/core/App.php) It functions as a router: parses URL, creates instance of [Controller](Framework/Controller), calls Method and matches View or just outputs JSON.

## Simple usage
```php
$app = new App;

$app->run()
```
```php
// change paths
$app->paths = [
  'controller'  =>  'myControllers', // folder for controllers
  'model'       =>  'myModel', // folder for models
  'app'         =>  'myApp' // app root
];

// set namespace
$app->settings['namespace'] = "MyApp"; 
```
## Step by Step

1. Parse URL (create array from URL string received as `$_GET['url']` parameter).

   > So we receive request like index.php?url=admin/page/edit, but we can use .htaccess to adjust it to /admin/page/edit

2. Sets Module, if registered.

3. Initializes Controller and if it does not exists it will call default controller, first parameter of URL for default module, otherwise second.

4. Calls Method. If not set or found it will call default method and if it does not exists it will throw error, second or third parameter of the URL.

5. If there are still some remaining parts of URL array, they will be passed as parameters to the method.

6. Calls view based on `module/controller/method` path in `app/views` folder

## URL 

- module [have to be registered]
- controller [default: Home], [sets the controller to be initialized]
- method [default: Index], [sets the method to be called]
- params [default: empty array], [parameters to be passed to method]

All those properties are set from url, and you can change their default value, before calling run.

## API

| name                        | default value | description                              |
| --------------------------- | ------------- | ---------------------------------------- |
| $paths['controllers']       | controllers   | path to your controllers                 |
| $paths['model']             | model         | path to your models                      |
| $paths['app']               | app           | path to your app                         |
| $settings['viewExtension']  | php           | used only if views are used, extension of a view file. |
| $settings['renderFunction'] | render        | render function to be called (render, renderTwig), again only if views are used. |
| $settings['namespace']      | app           | namespace of your app                    |

All those properties have to be set correctly before app is run .

## Example Requests

| Route                                    | Description                              |
| ---------------------------------------- | ---------------------------------------- |
| /module/controller/method/param1/.../paramN | Ideal URL Structure                      |
| /admin/posts/edit/my-first-post          | Calls `edit` method with `my-first-post` parameter of `posts` controller in `admin` module and matches view. |
| /posts/edit-my-third-post                | This one `uses the default module`, so it does not have to be in url. |
| /                                        | This calls default module, default controller and default method with no parameters. |

