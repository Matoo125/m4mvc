<?php 
namespace m4\m4mvc\helper\user;

use m4\m4mvc\helper\Redirect;
use m4\m4mvc\core\Controller;
use m4\m4mvc\helper\Session;

class UserController extends Controller 
{
	public function __construct()
	{
		$this->model = new UserModel();
	}

	public function create_user_table($sql = null)
	{
		if (!$sql) {
			$sql = "CREATE TABLE users(
						  id INT AUTO_INCREMENT PRIMARY KEY,
						  username VARCHAR(40) DEFAULT NULL,
						  slug VARCHAR(128) NOT NULL,
						  password VARCHAR(255) NOT NULL,
						  first_name VARCHAR(40) DEFAULT NULL,
						  last_name VARCHAR(40) DEFAULT NULL,
						  about_me TEXT DEFAULT NULL,
						  email VARCHAR(255) NOT NULL,
						  role INT(1) NOT NULL,
						  image_id int(11) DEFAULT NULL,
						  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
						);";
		}

		$this->model->save($sql);

		echo 'Table users has been created';
	}

	public function login()
	{
		if (Session::get('user_id'))  Redirect::to('/');

   		if (!$_POST || !$_POST['email'] || !$_POST['password']) return;

   		if (!$user = $this->model->getByEmail($_POST['email'])) {
   		    return json_decode([
   		    		'success'		=> 	false,
   		    		'code'			=>	404 // user does not exists
   		    	]);
   		}

   		if (password_verify($_POST['password'], $user['password'])) {
   		    Session::set('user_id', $user['id']);
   		    return json_decode([
   		    		'success'		=> 	true,
   		    		'reason'		=>	200 // success
   		    	]);
   		} 

   		else {
   		    return json_decode([
   		    		'success'		=> 	true,
   		    		'reason'		=>	401 // Credentials does not match
   		    	]);
   		}

	}

	public function logout()
	{
	  Session::destroy();
	  Redirect::to("/");
	}

}