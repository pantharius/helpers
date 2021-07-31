<?php
namespace JDOUnivers\Helpers;

use JDOUnivers\Helpers\DB\EntityManager;
use JDOUnivers\Helpers\DB\Query;

class Jetons{

  const JETON_VALUE_EUR = 0.001;

  public static function eurToJetons($montant){
    return $montant/self::JETON_VALUE_EUR;
  }

  public static function addJetons($iduser,$amount,$reason){
    $EM = EntityManager::getInstance();
    $newJetonhistory = new \Jetonhistory();
    $newJetonhistory->userid = $iduser;
    $newJetonhistory->amount = $amount;
    $newJetonhistory->reason = $reason;
    $newJetonhistory->historydate = Date::NowToString();
    $EM->save($newJetonhistory);

    $usersSQL = new Query("users");
    $usersSQL->update("jdojetons = jdojetons + ?","id = ?",array($amount,$iduser));
  }

}
