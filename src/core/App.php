<?php 
namespace m4\m4mvc\core;

use m4\m4mvc\helper\Request;


class App
{
	public $paths = [
		'controllers' => '../controllers',
		'theme'		  => false, 		// theme might be array with path to each module theme
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
		if ($this->settings['modules']) {
			$url = Module::set($url);
			if (!Module::$active) {
				throw new \Exception('You need to register modules before you can use them');
			}
		}

		// handle request
		$request = Request::handle();

		// create instance of controller
		$url = $this->setController($url);

		// call the method
		$this->callMethod($url);
	}

	private function setController($url)
	{ 

		if ($url && file_exists($this->paths['controllers'] . DIRECTORY_SEPARATOR . Module::$active . DIRECTORY_SEPARATOR . ucfirst($url[0] . '.php'))) {
			$this->controller = ucfirst($url[0]);
		    array_shift($url);
		}

		// check default controller
		$path = $this->paths['controllers'] . '/' . Module::$active . '/' . $this->controller . '.php';
		if (!file_exists($path)) {
			$error = 'Default controller: "' . $path . '" does not exists';
			throw new \Exception($error);
		}

		if ($this->settings['namespace']) {
			$class = $this->settings['namespace'] . '\\' . str_replace('/', '\\', $this->paths['controllers']) . '\\';
			$class .= $this->settings['modules'] ? Module::$active . '\\' : '';
			$class .= $this->controller;
		} else {
			$class = $this->controller;
		}

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

		if (method_exists($this->instance, $this->method)) {

			call_user_func_array([$this->instance, $this->method], $this->params);

			return $this->response == 'json'  ? 
						 $this->render('json') : 
						 $this->render('theme');
		} else {
			$error = 'Method: ' . $this->method . ' of controller: ' . $this->controller . ' does not exists';
			throw new \Exception($error);
		}

	}

	private function render($status)
	{
		switch ($status) {
			case 'theme':

				$view = '';

				if ($this->paths['theme']) {
					if (is_string($this->paths['theme'])) {
						$view = $this->paths['theme'];
					} else {
						$view = $this->paths['theme'][Module::$active];
					}
				}

				$folder = $view;
				if ($this->settings['moduleView']) { $folder .= DIRECTORY_SEPARATOR . Module::active; }

				$folder .= DIRECTORY_SEPARATOR . ucfirst($this->controller) . DIRECTORY_SEPARATOR .  $this->method;

				$viewPath =   $folder . '.' . $this->settings['viewExtension'];

				if (file_exists($viewPath)) {
					$this->instance->pathToTheme = $view;

					call_user_func_array([$this->instance, $this->settings['renderFunction']], [$viewPath]);
				} else {
					echo 'view: ' . $viewPath . ' could not be found';
				}
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
			return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}


}

