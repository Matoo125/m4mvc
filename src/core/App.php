<?php 
namespace m4\m4mvc\core;

use m4\m4mvc\helper\Request;

class App
{
  public $paths = [
    // required path to controllers folder, relative to app
    'controllers' => false, 
    // required path to models folder, relative to app
    'model'       => false,
    // required path to base app folder, relative to index or absolute
    'app'       =>  false,
    // not done yet path to logs folder, relative to app 
    'log'       =>  false 
  ];

  public $settings = [
    'debug'           =>  false, // not done yet
    'viewExtension'   =>  'php',
    'renderFunction'  => 'render',
    'namespace'       =>  null
  ];

  // Controller to be instantized
  public $controller = 'Home';
  // Instance of controller object
  private $instance = null;
  // Method of controller to be called
  public $method = 'Index';
  // Params to be passed to method
  public $params = [];

  public function run()
  {
    // handle request
    $url = Request::handle();

    // set module from url
    if (Module::$active AND is_array($url)) { $url = Module::set($url); }

    // create instance of controller
    $url = $this->setController($url);

    // call the method
    $this->callMethod($url);
  }

  private function findControllerInUrl ($url)
  {
    $path = $this->paths['app'] . DIRECTORY_SEPARATOR . 
            $this->paths['controllers'] . DIRECTORY_SEPARATOR . 
            Module::$active . DIRECTORY_SEPARATOR . 
            ucfirst($url[0] . '.php');
    if (file_exists($path)) {
      $this->controller = ucfirst(array_shift($url));
    }

    return $url;
  }

  private function prependNamespace ()
  {
    if (!$this->settings['namespace']) { return $this->controller; }

    $class = $this->settings['namespace'] . '\\' . 
             str_replace('/', '\\', $this->paths['controllers']) . '\\';
    $class .= Module::$active ? Module::$active . '\\' : '';
    $class .= $this->controller;

    return $class;
  }

  private function setController($url)
  { 
    if ($url) $url = $this->findControllerInUrl($url);

    $path =  $this->paths['app'] . DIRECTORY_SEPARATOR. 
             $this->paths['controllers'] . DIRECTORY_SEPARATOR . 
             Module::$active . DIRECTORY_SEPARATOR . 
             $this->controller . '.php';

    if (!file_exists($path)) {
      $error = 'Controller: "' . $path . '" does not exists';
      throw new \Exception($error);
    }

    $class = $this->prependNamespace();

    if (!class_exists($class)) {
      $error = 'Class: "' . $class . '" does not exists ';
      throw new \Exception($error);
    }
    $this->instance = new $class;

    return $url;
  }

  private function callMethod($url)
  {
    // set the method 
    if (isset($url[0]) && method_exists($this->instance, $url[0])) {
      $this->method = $url[0];
      array_shift($url);
    }

    // set the params
    $this->params = is_array($url) ? $url : [];

    if (!method_exists($this->instance, $this->method)) {
      $error = 'Method: ' . $this->method . 
               ' of controller: ' . $this->controller . 
               ' does not exists';
      throw new \Exception($error);
    } 

    call_user_func_array(
      [$this->instance, $this->method], 
      $this->params
    );

    return Module::render() === 'json' ? 
           call_user_func([$this->instance, 'json']) : 
           $this->render();

  }

  private function render()
  {
    $view = $this->paths['app'] . DIRECTORY_SEPARATOR;
    $view .= Module::folder() . DIRECTORY_SEPARATOR;

    $this->instance->pathToTheme = $view;

    $view .= ucfirst($this->controller) . DIRECTORY_SEPARATOR .
             $this->method . '.' . $this->settings['viewExtension'];

    call_user_func_array(
      [$this->instance, $this->settings['renderFunction']], 
      [$view]
    );
  }


  public function db (array $credentials) 
  {
    Model::$credentials = $credentials;
    if ($this->settings['namespace']) {
      Controller::$modelNamespace = (
        $this->settings['namespace'] . 
        '\\' . str_replace('/', '\\', $this->paths['model']) . '\\'
      );
    }
  }




}

