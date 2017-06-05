<?php 

namespace m4\m4mvc\helper\user;

use m4\m4mvc\core\Model;

class UserModel extends Model 
{
	public function getByEmail($email) {
	    $db = static::getDB();
	    $stmt = $db->prepare("select * from users where email = :email LIMIT 1");
	    $stmt->execute(array(':email' => $email));
	    if ($results = $stmt->fetch()) {
	        return $results;
	    }
	    return null;
	}
}