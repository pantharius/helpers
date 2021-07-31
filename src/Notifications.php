<?php
/**
 * Notifications Helper
 *
 */
namespace JDOUnivers\Helpers;

use JDOUnivers\Helpers\DB\EntityManager;
use JDOUnivers\Helpers\DB\Query;

class Notifications
{

    //1xxx: general notifications
    //2xxx: level2 notifications
    //3xxx: academie notifications
    const CODE_NEWELEVEFORMATION = 3004;
    const CODE_NEWELEVESEMINAIRE = 3005;
    const CODE_NEWAVIS = 3006;

    public static function createNotification($type, $parametters, $userid){
        $EM = EntityManager::getInstance();
        $newNotification = new \Notifications();
        $newNotification->type = $type;
        $newNotification->parametters = json_encode($parametters);
        $newNotification->userid = $userid;
        $newNotification->creationdate = Date::NowToString();
        $newNotification->readdate = null;
        $EM->save($newNotification);
    }

    public static function readNotification($notificationid){
        $notificationsSQL = new Query("notifications");
        $notificationsSQL->update("readdate = NOW()", "id = ?", array($notificationid));
    }

}