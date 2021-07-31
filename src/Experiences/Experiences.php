<?php
/**
 * Experiences Helper
 */
namespace JDOUnivers\Helpers\Experiences;

use JDOUnivers\Helpers\DB\EntityManager;
use JDOUnivers\Helpers\DB\Query;
use JDOUnivers\Helpers\Date;

class Experiences
{
    const firstLevelXpNeeded = 100;
    const XpFactorPerLevel = 2;

    public static function getLevelNeededExp(int $level): int{
        if($level <= 1) return 0;
        return self::firstLevelXpNeeded*pow(self::XpFactorPerLevel,$level-2);
    }

    public static function getExpSinceStart(int $level, int $exp): int{
        if($level < 1) return 0;
        return self::firstLevelXpNeeded*(pow(self::XpFactorPerLevel,$level-1)-1)+$exp;
    }

    public static function addRewardToUser($siteuser, $rewardReasonId){
        if(self::isLimitUserHistoryIsReached($siteuser, $rewardReasonId))
            return false;

        self::addExpToUser($siteuser, self::RewardReasons()[$rewardReasonId]->reward);
        self::addExpHistoryToUser($siteuser, $rewardReasonId, self::RewardReasons()[$rewardReasonId]->reward);
        return true;
    }

    private static function isLimitUserHistoryIsReached($siteuser, $rewardReasonId){
        $rewardReason = self::RewardReasons()[$rewardReasonId];
        if($rewardReason->limit->type == ExperienceLimit::None) return false;

        $experiencehistorySQL = new Query("experiencehistory");
        $experiencehistory = $experiencehistorySQL->prepareFindWithCondition("siteuserid=? AND experienceid=?",array($siteuser->id,$rewardReasonId))->orderBy("givendatetime DESC")->execute();
        
        if($rewardReason->limit->type == ExperienceLimit::Unique && count($experiencehistory) < $rewardReason->limit->amount) return false;
        if($rewardReason->limit->type == ExperienceLimit::Hour && count($experiencehistory) > 0 && Date::isHourPastFromNowMoreThan($experiencehistory[0]->givendatetime,$rewardReason->limit->amount)) return false;
        
        return true;
    }

    private static function addExpToUser($siteuser, $exp){
        $EM = EntityManager::getInstance();
        $expWillBe = $siteuser->experience + $exp;
        $expNeededToLevelUp = self::getLevelNeededExp($siteuser->level+1);
        if($expWillBe >= $expNeededToLevelUp){
            $siteuser->level += 1;
            $siteuser->experience = $expWillBe-$expNeededToLevelUp;
        }else{
            $siteuser->experience = $expWillBe;
        }
        $EM->save($siteuser);
    }

    private static function addExpHistoryToUser($siteuser, $rewardReasonId, $exp){
        $EM = EntityManager::getInstance();
        $newExpHistory = new \Experiencehistory();
        $newExpHistory->experienceid = $rewardReasonId;
        $newExpHistory->reward = $exp;
        $newExpHistory->givendatetime = Date::NowToString();
        $newExpHistory->siteuserid = $siteuser->id;
        $EM->save($newExpHistory);
    }
    
    public static function RewardReasons(){
        return array(
            "EXP001" => new ExperienceRewardReason("Connexion au site",1,new ExperienceLimit(ExperienceLimit::Hour,12)),
            "EXP002" => new ExperienceRewardReason("Ajout de l’avatar au profile",10,new ExperienceLimit(ExperienceLimit::Unique,1)),
            "EXP003" => new ExperienceRewardReason("Première connexion à un jeu gratuit",20,new ExperienceLimit(ExperienceLimit::Unique,1)),
            "EXP004" => new ExperienceRewardReason("Connexion à un jeu gratuit",5,new ExperienceLimit(ExperienceLimit::Hour,12)),
            "EXP005" => new ExperienceRewardReason("Achat d’un jeu payant",50,new ExperienceLimit(ExperienceLimit::Unique,1)),
            "EXP006" => new ExperienceRewardReason("Connexion à un jeu payant",10,new ExperienceLimit(ExperienceLimit::Hour,12)),
            "EXP007-01" => new ExperienceRewardReason("Trouver la première fin de Blackout",20,new ExperienceLimit(ExperienceLimit::Unique,1)),
            "EXP007-02" => new ExperienceRewardReason("Finir la première quete de Sillage",20,new ExperienceLimit(ExperienceLimit::Unique,1)),
            "EXP008" => new ExperienceRewardReason("Accès à une nouvelle gratuite",5,new ExperienceLimit(ExperienceLimit::Hour,12)),
            "EXP009" => new ExperienceRewardReason("Achat d’une nouvelle payante",30,new ExperienceLimit(ExperienceLimit::None,0)),
            "EXP010" => new ExperienceRewardReason("Achat d’un micro pack de jetons",5,new ExperienceLimit(ExperienceLimit::None,0)),
            "EXP011" => new ExperienceRewardReason("Achat d’un pack Excellence",50,new ExperienceLimit(ExperienceLimit::None,0)),
            "EXP012" => new ExperienceRewardReason("Achat d’un pack Empereur",250,new ExperienceLimit(ExperienceLimit::None,0)),
            "EXP013" => new ExperienceRewardReason("Ajout d’un compte filleul",100,new ExperienceLimit(ExperienceLimit::None,0))
        );
    }
}