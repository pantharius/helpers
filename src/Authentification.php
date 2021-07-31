<?php
namespace JDOUnivers\Helpers;

use JDOUnivers\Helpers\Errors;
use JDOUnivers\Helpers\Request;
use JDOUnivers\Helpers\DB\Query;
/**
 * Authentification Helper
 *
 */

class Authentification
{   
    public static function getSessionFromHeader(){
        $sessionkey = Request::getSessionKey();
        $loginsessionsSQL = new Query("loginsessions");
        $loginsession = $loginsessionsSQL->getByKey($sessionkey);

        if ($loginsession == null)
            return new Errors("session", new \Exception("Votre session a expiré. Veuillez vous reconnecter.", Errors::CODE_SESSIONKEY));
        return $loginsession;
    }
}