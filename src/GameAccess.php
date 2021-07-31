<?php
/**
 * GameAccess Helper
 *
 */
namespace JDOUnivers\Helpers;

use JDOUnivers\Helpers\DB\Query;


class GameAccess
{
    public static function isPurshased($gameid, $userid){
      $purchasedSQL = new Query("purchased");
      return ($purchasedSQL->prepareFindWithCondition("gameid=? AND userid=?",array($gameid,$userid))->execut() != null);
    }
}
