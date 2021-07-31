<?php
/**
 * Mail Helper
 */

namespace JDOUnivers\Helpers;

class PostRequester 
{

    public static function post($url,$data){

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		return $result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }
    }
}
