<?php 
namespace m4\m4mvc\core;

use m4\m4mvc\helper\Request;


class App
{
	public $paths = [
		'controllers' => '../controllers',
		'app'				=>	false, // required
		'theme'		  => false,  // theme might be array with path to each module theme
		'log'				=>	false
	];

	public $settings = [
		'debug'						=>	false,
		'moduleView' 			=> false,
		'modules'		 			=>	false,
		'viewExtension'		=>	'php',
		'renderFunction'	=> 'render',
		'namespace'				=>	null
	];

	// Controller to be instantized
	public $controller = 'Home';
	// Instance of controller
	private $instance = null;
	// Method of controller to be called
	public $method = 'Index';
	// Params to be passed to method
	public $params = [];
	// Type of response
	public $response = 'view';


	public function run()
	{
		// create cleaned array from url
		$url = $this->parseUrl();

		// module handler
		if (Module::$active) {
			$url = Module::set($url);
			if (!Module::$active) {
				$msg = 'You need to register modules before you can use them';
				throw new \Exception($msg);
			}
		}

		// handle request
		$request = Request::handle();

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
		if (!file_exists($path)) {
			$msg = '404 path ' . $path . ' does not exists';
			throw new \Exception($msg);
		}
		$this->controller = ucfirst($url[0]);
	  return array_shift($url);
	}

	private function configureNamespace ()
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

		$path = (isset($this->paths['app']) ? $this->paths['app'] . '/' : '') . 
						 $this->paths['controllers'] . '/' . 
						 Module::$active . '/' . 
						 $this->controller . '.php';

		if (!file_exists($path)) {
			$error = 'Controller: "' . $path . '" does not exists';
			throw new \Exception($error);
		}

		$class = $this->configureNamespace();

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
		$this->params = $url ? $url : [];

		if (!method_exists($this->instance, $this->method)) {
			$error = 'Method: ' . $this->method . 
							 ' of controller: ' . $this->controller . 
							 ' does not exists';
			throw new \Exception($error);
		} 

		call_user_func_array([$this->instance, $this->method], $this->params);

		return $this->response == 'json'  ? 
					 $this->render('json') : 
					 $this->render('theme');

	}

	private function render($status)
	{
		switch ($status) {
			case 'theme':

				$view = $this->paths['app'] . DIRECTORY_SEPARATOR;

				if ($this->paths['theme']) {
					if (is_string($this->paths['theme'])) {
						$view .= $this->paths['theme'];
					} else {
						$view .= $this->paths['theme'][Module::$active];
					}
				}

				$folder = $view;
				if ($this->settings['moduleView']) { 
					$folder .= DIRECTORY_SEPARATOR . Module::$active; 
				}

				$folder .= DIRECTORY_SEPARATOR . ucfirst($this->controller) . 
									 DIRECTORY_SEPARATOR .  $this->method;

				$viewPath =   $folder . '.' . $this->settings['viewExtension'];

				$this->instance->pathToTheme = $view;

				call_user_func_array(
					[$this->instance, $this->settings['renderFunction']], 
					[$viewPath]
				);

				break;
			case 'json': 
					call_user_func([$this->instance, 'json']);
				break;
			default:
				echo '404 page not found';
				break;
		}
	}


	/* helpers */
	public function useTwig () 
	{
		$this->settings['viewExtension'] = 'twig';
		$this->settings['renderFunction'] = 'renderTwig';
	}

	public function db (array $credentials, string $namespace = null) 
	{
		Model::$credentials = $credentials;
		if ($namespace) {
			Controller::$modelNamespace = $namespace;
		} else {
			Controller::$modelNamespace = $this->settings['namespace'] . '\\model\\';
		}
	}

	private function parseUrl()
	{
		if (isset($_GET['url'])) {
			return explode(
				'/', filter_var(
					rtrim(
						$_GET['url'], 
						'/'
					), 
					FILTER_SANITIZE_URL
				)
			);
		}
	}


}

