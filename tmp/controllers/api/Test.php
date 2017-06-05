<?php 

namespace tmp\controllers\api;

use m4\m4mvc\core\Controller;

class Test extends Controller
{
	public function __construct()
	{
		$this->model = $this->getModel('Test');
	}

	public function index()
	{
		$this->data['your_path'] = 'test/index';
		// $this->model->create_table();
	}

	public function another()
	{
		$this->data['your_path']	=	'/test/another';
		/* You can get params like this, or use usual param catchers (param1, param2) */
		$this->data['params'] = func_get_args();
		/* For $_GET superglobal to work you need to set .htaccess */
		$this->data['get'] = $_GET;
	}
} 

