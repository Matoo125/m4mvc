<?php 

namespace tmp\controllers\api;

use m4\m4mvc\core\Controller;

class Home extends Controller
{
	public function index()
	{
		$this->data['location'] = 'home';
	}
} 

