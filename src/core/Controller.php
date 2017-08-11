<?php 
namespace m4\m4mvc\core;

class Controller 
{
	// Model object is stored here
	protected $model = null;
	// Namespaces for models
	public static $modelNamespace = null;
	// View to be called
	protected $view = null;
	// Data to be passed
	protected $data = [];
	// Static response
	public static $response = [];
	// path to theme
	public $pathToTheme = null;

	public function getModel($name)
	{
		$model = self::$modelNamespace . $name;
		return new $model;
	}

	public function render($view = null)
	{
		if ($view) {
			require_once($view);
		}

	}

	public function renderTwig($view = null)
	{
		if ($this->view) {
			$view = $this->view;
		}
		///////////////// DECLARE TWIG INSTANCE /////////////////
		$loader = new \Twig_Loader_Filesystem($this->pathToTheme);
		$twig = new \Twig_Environment( $loader, array(
		  'debug' => true,
		) );
		$twig->addExtension(new \Twig_Extension_Debug());
		///////////////// ADD GLOBALS /////////////////
		$twig->addGlobal("session", $_SESSION);
		///////////////// Create filters /////////////////
		$slugifilter = new \Twig_Filter('slugify', '\\m4\\m4mvc\\helper\\Str::slugify');
		$twig->addFilter($slugifilter);
		///////////////// ADD DATA TO ARRAY /////////////////
		$this->data['sessionclass'] = new \m4\m4mvc\helper\Session;
		// pass lang and url arrays
		$this->data['lang'] = \m4\m4mvc\helper\Str::getLang();
		$this->data['url']  = \m4\m4mvc\helper\Str::getUrl();
		///////////////// RENDER TWIG TEMPLATE /////////////////
		echo $twig->render($view, $this->data);
	}

	public function json()
	{
		header('Content-Type: application/json');
		echo json_encode(array_merge($this->data, self::$response));
	}
	
}