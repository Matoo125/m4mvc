<?php 

namespace m4\m4mvc\helper;

use m4\m4mvc\core\Controller;

/*
 *  Response helper class
 *  helps with response operations
 */

class Response
{
	public static $response = [];

	public static $code = 200;

	public static $errorCode = 400;

	public static function code ($code) 
	{
		self::$code = $code;
	}

	public static function error ($message, $extra = [], $code = null)
	{
		self::$code = $code ?? self::$errorCode;
		self::create('ERROR', $message, $extra);
	}

	public static function success ($message, $extra = [])
	{
		self::create('SUCCESS', $message, $extra);
	}

	public static function create ($status, $message = null, $extra = []) 
	{
		self::$response = [
			'status'	=>	$status,
			'message'	=>	$message,
		];
		self::$response = array_merge(self::$response, $extra);
		self::json(self::$response);
	}

	public static function json () 
	{
		header('Content-Type: application/json', false);
		http_response_code(self::$code);
		echo json_encode(self::$response);
		exit();
	}
}