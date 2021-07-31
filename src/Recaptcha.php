<?php

/**
 * The reCAPTCHA server URL's
 */
namespace JDOUnivers\Helpers;

class Recaptcha
{

    public static function get_html()
    {
        return '<div class="g-recaptcha" style="align-items: center;" data-sitekey="'. getenv("RECAPTCHA_PUBLIC_KEY") .'"></div>';
    }

    public static function get_response($captcha)
    {
        try {

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                     'secret'   => getenv("RECAPTCHA_PRIVATE_KEY"),
                     'response' => $captcha,
                     'remoteip' => $_SERVER['REMOTE_ADDR']
                    );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            return json_decode($result)->success;
        }
        catch (Exception $e) {
            return null;
        }
    }

}
