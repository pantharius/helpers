<?php
/**
 * Exceptions Helper
 *
 */
namespace JDOUnivers\Helpers;

class Exceptions
{
    public static function tojson($e){
      if(DEV){
        return json_encode(array(
          "Exception" => array(
            "Message" => $e->getMessage(),
            "Code" => $e->getCode(),
            "File" => $e->getFile(),
            "Line" => $e->getLine(),
            "Class" => get_class($e),
            "Trace" => $e->getTraceAsString()
          )
        ));
      }else{
        return json_encode(array(
          "Exception" => array(
            "Message" => $e->getMessage(),
            "Code" => $e->getCode()
          )
        ));
      }
    }
}
