<?php
namespace JDOUnivers\Helpers;

use JDOUnivers\Helpers\DB\EntityManager;
use JDOUnivers\Helpers\DB\Query;
/**
 * Attempts Helper
 *
 */

class Attempts
{
    public static function isAttemptsLock($userid){
      $loginattemptsSQL = new Query("loginattempts");
      $nbattempts2h = count($loginattemptsSQL->prepareFindWithCondition("`userid`=? AND blocked=b'0' AND DATE_ADD(`attemptdatetime`, INTERVAL 2 HOUR) > ? AND `connecteddatetime` IS NULL",array($userid,Date::NowToString()))->execute());
      if($nbattempts2h>=13)
        return "nbattempts2h";
      $nbattempts1h = count($loginattemptsSQL->prepareFindWithCondition("`userid`=? AND blocked=b'0' AND DATE_ADD(`attemptdatetime`, INTERVAL 1 HOUR) > ? AND `connecteddatetime` IS NULL",array($userid,Date::NowToString()))->execute());
      if($nbattempts1h>=8)
        return "nbattempts1h";
      $nbattempts30min = count($loginattemptsSQL->prepareFindWithCondition("`userid`=? AND blocked=b'0' AND DATE_ADD(`attemptdatetime`, INTERVAL 30 MINUTE) > ? AND `connecteddatetime` IS NULL",array($userid,Date::NowToString()))->execute());
      if($nbattempts30min>=5)
        return "nbattempts30min";
      $nbattempts5min = count($loginattemptsSQL->prepareFindWithCondition("`userid`=? AND blocked=b'0' AND DATE_ADD(`attemptdatetime`, INTERVAL 5 MINUTE) > ? AND `connecteddatetime` IS NULL",array($userid,Date::NowToString()))->execute());
      if($nbattempts5min>=3)
        return "nbattempts5min";
      return true;
    }

    public static function reset($userid){
      $loginattemptsSQL = new Query("loginattempts");
      $loginattemptsSQL->update("`connecteddatetime`=?","`userid`=? AND `connecteddatetime` IS NULL",array(Date::NowToString(),$userid));
    }
    public static function register($userid,$blocked=false){
      $EM = EntityManager::getInstance();
      $newLoginattempts = new \Loginattempts();
      $newLoginattempts->userid = $userid;
      $newLoginattempts->attemptdatetime = Date::NowToString();
      $newLoginattempts->blocked = $blocked;
      $EM->save($newLoginattempts);
    }
}
