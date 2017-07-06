<?php 
namespace m4\m4mvc\core;

use m4\m4mvc\helper\Request;

/*
 * This is the framework's brain
 * 1. URL is parsed
 * 2. Module is called
 * 3. Controller is instantiated
 * 4. Method is called and params are passed
 */

class App
{
	// paths to be used to find controllers and views
	public $paths = [
		'controllers' => '../controllers',
		'views'		  => false
	];
	// other settings
	public $settings = [
		'viewExtension'	=>	'php',
		'renderFunction' => 'render',
		'namespace'		=>	'app'
	];
	// Module [or folder] to be used to find controller
	public $module = 'api';
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

	/** 
	 *	Run the application 
	 **/
	public function run()
	{
		// create cleaned array from url
		$url = $this->parseUrl();

		// set the current module
		$url = $this->setModule($url);

		// handle request
		$request = Request::handle();

		// create instance of controller
		$url = $this->instantiateController($url);

		// call the method
		$this->callMethod($url);
	}

	public function db(array $credentials, string $namespace = null) {
		Model::$credentials = $credentials;
		if ($namespace) {
			Controller::$modelNamespace = $namespace;
		} else {
			Controller::$modelNamespace = $this->settings['namespace'] . '\\model\\';
		}
	}

	private function checkControllerFolder() {
		// check controllers folder
		if (!file_exists($this->paths['controllers'])) {
			$error = 'Path: ' . $this->paths['controllers'] . ' does not exists'; 
			throw new \Exception($error);
		}
	}

	private function checkController() {
		// check default controller
		$path = $this->paths['controllers'] . '/' . $this->module . '/' . $this->controller . '.php';
		if (!file_exists($path)) {
			$error = 'Default controller: ' . $path . ' does not exists';
			throw new \Exception($error);
		}

		$class = $this->settings['namespace'] . '\\controllers\\' . 
			     $this->module . '\\' . $this->controller;
		if (!class_exists($class)) {
			$error = 'Class: ' . $class . ' does not exists';
			throw new \Exception($error);
		}
	}

	/*
	* parseURL no arguments
	* returns url array
	* or null
	*/
	private function parseUrl()
	{
		if (isset($_GET['url'])) {
			return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}

	/* 	
	 * 	Set module from first url param
	 */
	private function setModule($url)
	{
		$this->checkControllerFolder();
		$modules = array_diff(scandir($this->paths['controllers']), ['.', '..', $this->module]);
		if (in_array($url[0], $modules)) {
			$this->module = $modules[array_search($url[0], $modules)];
			array_shift($url);
		}
		return $url;
	}

	/*
	 *	Create instance of controller
	 *	use default or take from url
	 */
	private function instantiateController($url)
	{ 
		// check if controller exists
		if ($url && file_exists($this->paths['controllers'] . DS . 
								$this->module . DS . 
								ucfirst($url[0]) . '.php')) {

			$this->controller = ucfirst($url[0]);
		    array_shift($url);
		}
		$this->checkController();
		// prepend namespaces
		$controller = $this->settings['namespace'] . '\\controllers\\' . 
					  $this->module . '\\' . $this->controller;
		$this->instance = new $controller;

		return $url;
	}

	/*
	 * 	Call method of controller
	 * 	and pass parameters
	 */
	private function callMethod($url)
	{
		// set the method if exists
		if (isset($url[0]) && method_exists($this->instance, $url[0])) {
			$this->method = $url[0];
			array_shift($url);
		}
		// set the params
		$this->params = $url ? $url : [];

		if (method_exists($this->instance, $this->method)) {
			// call the method
			call_user_func_array([$this->instance, $this->method], $this->params);

			return $this->module == 'api' || 
						 $this->response == 'json' || 
						 $this->paths['views'] === false ? 
						 $this->callView('json') : 
						 $this->callView(200);
		} else {
			$error = 'Method: ' . $this->method . ' of controller: ' . $this->controller . ' does not exists';
			throw new \Exception($error);
		}

	}

	private function callView($status)
	{
		switch ($status) {
			case 200:
				// module/controller/method
				$view = $this->module . DS . lcfirst($this->controller) . DS .  $this->method;
				$viewPath = $this->paths['views'] . DS . $view . '.' . $this->settings['viewExtension'];
				if (file_exists($viewPath)) {
					call_user_func_array([$this->instance, $this->settings['renderFunction']], [$view . '.' . $this->settings['viewExtension']]);
				} else {
					echo 'view: ' . $view . ' could not be found';
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

	public function useTwig () 
	{
		$this->settings['viewExtension'] = 'twig';
		$this->settings['renderFunction'] = 'renderTwig';
	}
}