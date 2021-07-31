<?php
namespace JDOUnivers\Helpers;

class InfosClient{

  public static function getIPAddress()
  {
    return $_SERVER["REMOTE_ADDR"];
  }

}
