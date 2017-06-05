<?php 

namespace tmp\model;

use m4\m4mvc\core\Model;

class Test extends Model 
{
	public function create_table()
	{
		$sql = "CREATE TABLE MyGuests (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				firstname VARCHAR(30) NOT NULL,
				lastname VARCHAR(30) NOT NULL,
				email VARCHAR(50),
				reg_date TIMESTAMP
				) ";

		$this->save($sql);
	}
}