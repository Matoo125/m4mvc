<?php 

namespace m4\m4mvc\helper;

use m4\m4mvc\helper\Response;

/*
 *  Request helper class
 *  helps with request operations
 */

class Request
{
	public static function forceMethod ($types)
	{
		if (is_string($types)) {
			$types = [$types];
		}

		$types = array_map('strtoupper', $types);

		if (!in_array(self::getRequestType(), $types)) {
			$message = 'This request type is not allowed: ';
			$message .= self::getRequestType();
			$message .= ', only those are: ' . json_encode($types);
			Response::error($message);
			return false;
		}

		return true;
	}

	public static function required ($data, $required)
	{
		$responseMessage = 'Required data not found ';
		$extra = [
			'data'	=>	$data,
			'required' => $required,
			'missing'	=>	[],
			'empty'		=>	[]
		];
		foreach ($required as $req) {
			if (!isset($data[$req])) {
				array_push($extra['missing'], $req);
			} elseif (isset($data[$req]) && empty($data[$req])) {
				array_push($extra['empty'], $req);
			}
		}
	 	return empty($extra['missing']) && empty($extra['empty']) ? true : Response::error($responseMessage, $extra);
	}

	public static function handle () 
	{
		switch (self::getRequestType()) {
			case 'POST':
				// get ajax json post data if request is empty
				$_POST = !empty($_POST) ? $_POST : self::jsonPost();
				break;
			
			default:
				# code...
				break;

		return self::getRequestType();
		}
	}

	public static function getRequestType ()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public static function jsonPost ()
	{
		return json_decode(file_get_contents('php://input'), true);
	} 
}