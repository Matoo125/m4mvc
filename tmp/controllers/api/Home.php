<?php 

namespace tmp\controllers\api;

use m4\m4mvc\core\Controller;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Request;

class Home extends Controller
{
	public function index()
	{
		if (!Request::forceMethod('get')) { return; }

		if (!Request::required($_GET, ['name', 'age'])) { return; }

		$this->data['location'] = 'home';

		Response::success('You have reached the end of the world');
	}
} 

