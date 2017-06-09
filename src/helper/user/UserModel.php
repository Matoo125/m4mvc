<?php 

namespace m4\m4mvc\helper\user;

use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Query;
use m4\m4mvc\helper\Str;

class UserModel extends Model 
{
	public static $table = 'users';

	public function getByEmail($email, $items = '*') {
		$query = $this->query->select($items)->from(self::$table)->where('email = :email')->build();
	    return $this->fetch($query, ['email' => $email]);
	}

	public function register($data)
	{
		$query = $this->query->insert('username', 'slug', 'password', 'email', 'role')
							 ->into(self::$table)
							 ->build();
		$args = [
			'username'  =>  $data['username'],
			'slug'	    =>  Str::Slugify($data['username']),
			'password'  =>	$data['password'],
			'email'		=>	$data['email'],
			'role'		=>	1
		];
		return $this->save($query, $args, true);
	}

}