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

	public function getModel($name)
	{
		$model = self::$modelNamespace . $name;
		return new $model;
	}

	public function render($view = null)
	{
		if ($view) {
			# code...
		}

	}

	public function renderTwig($view = null)
	{

	}

	public function json()
	{
		header('Content-Type: application/json');
		echo json_encode(array_merge($this->data, self::$response));
	}
	
}