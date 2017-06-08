<?php 
namespace m4\m4mvc\helper\user;

use m4\m4mvc\helper\Redirect;
use m4\m4mvc\core\Controller;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;

class UserController extends Controller 
{
	public function __construct()
	{
		$this->model = new UserModel();
	}

	public function create_user_table($sql = null)
	{
		if (!$sql) {
			$sql = file_get_contents('tables.sql');
		}

		$this->model->save($sql);

		echo 'Table users has been created';
	}

	public function login()
	{
		Request::forceMethod('post');
		Request::required($_POST, ['email', 'password']);

		$user = $this->model->getByEmail($_POST['email'], 'id, email, username, password ');

		if (!$user) {
		    Response::error('User does not exists');
		}

		if (!password_verify($_POST['password'], $user['password'])) {
			Response::error('Credentials do not match');
		} 

	    Session::set('user_id', $user['id']);
	    Response::success(
	    	'You are logged in. ', 
	    	['user'=>array_diff_key($user, array_flip(['password']))]
	    );

	}

	public function register () 
	{
		Request::forceMethod('post');
		Request::required($_POST, ['username', 'email', 'password', 'passwordCheck']);

		$data['username'] 		=	$_POST['username'];
		$data['email'] 			=	$_POST['email'];
		$data['password'] 		= 	$_POST['password'];
		$data['passwordCheck']	=	$_POST['passwordCheck'];

		if ($data['password'] !== $data['passwordCheck']) {
			Response::error('Passwords do not match');
		}

		if ($this->model->getByEmail($data['email'], 'id')) {
			return Response::error('This email already exists');
		}

		$data['password']	=	password_hash($data['password'], PASSWORD_DEFAULT);

		if ($id = $this->model->register($data)) {
			Session::set('user_id', $id);
			Response::success(
				'You have been registered. ', 
	    		['user'	=>	[
	    			'username'	=>	$data['username'],
	    			'email'		=>	$data['email'],
	    			'id'		=>	$id
	    			]
	    		]
			);
		}
	}

	public function logout()
	{
		Session::destroy();
		Response::success('You have been logged out');
	}

	public function is_logged_in () 
	{
	  Response::create(Session::get('user_id'));
	}

}