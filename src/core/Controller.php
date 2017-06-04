<?php 
namespace m4\m4mvc\core;

class Controller 
{
	// Model object is stored here
	protected $model = null;
	// View to be called
	protected $view = null;
	// Data to be passed
	protected $data = [];


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
		echo json_encode($this->data);
	}
}