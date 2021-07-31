<?php
/**
 * Request Class
 */

namespace JDOUnivers\Helpers;

/**
 * It contains the request information and provide methods to fetch request body.
 */
class Request
{
	/**
	 * Safer and better access to $_POST.
	 *
	 * @param  string   $key
	 * @static static method
	 *
	 * @return mixed
	 */
	public static function post($key)
	{
		if($key=="")
			return self::isPostExist() ? $_POST : null;
		return array_key_exists($key, $_POST)? $_POST[$key]: null;
	}

	/**
	 * Safer and better access to $_FILES.
	 *
	 * @param  string   $key
	 * @static static method
	 *
	 * @return mixed
	 */
	public static function files($key)
	{
		return array_key_exists($key, $_FILES)? $_FILES[$key]: null;
	}

	/**
	 * Safer and better access to $_GET.
	 *
	 * @param  string   $key
	 * @static static method
	 *
	 * @return mixed
	 */
	public static function query($key)
	{
		return array_key_exists($key, $_GET)? $_GET[$key]: null;
	}

	/**
	 * Detect if request is Ajax.
	 *
	 * @static static method
	 *
	 * @return boolean
	 */
	public static function isAjax()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
		}
		return false;
	}

	/**
	 * Detect if request is POST request.
	 *
	 * @static static method
	 *
	 * @return boolean
	 */
	public static function isPost()
	{
		return $_SERVER["REQUEST_METHOD"] === "POST";
	}

	/**
	 * Detect if Post is set and not empty
	 *
	 * @static static method
	 *
	 * @return boolean
	 */
	public static function isPostExist()
	{
		return (isset($_POST) && !empty($_POST));
	}

	/**
	 * Detect if request is GET request.
	 *
	 * @static static method
	 *
	 * @return boolean
	 */
	public static function isGet()
	{
		return $_SERVER["REQUEST_METHOD"] === "GET";
	}

	/**
	 * Detect if Post is set and not empty
	 *
	 * @static static method
	 *
	 * @return boolean
	 */
	public static function isGetExist()
	{
		return (isset($_GET) && !empty($_GET));
	}

	/**
	 * Detect if request is GET request.
	 *
	 * @static static method
	 *
	 * @return boolean
	 */
	public static function isOptions()
	{
		return $_SERVER["REQUEST_METHOD"] === "OPTIONS";
	}

	/**
	 * Get header Authorization
	 */
	public static function getSessionKey(){
		$headers = apache_request_headers();
		if (isset($headers['x-session-key'])) {
			return $headers['x-session-key'];
		}
		return null;
	 }
}
