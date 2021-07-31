<?php
namespace JDOUnivers\Helpers;

use JDOUnivers\Helpers\DB\Query;
use JDOUnivers\Helpers\DB\EntityManager;


class KeyGenerator {

  public static function sha512($data){
    return hash("sha512",$data);
  }

  /**
   * Genere une session key
   *
   * @return string   returns a SessionKey
   */
  public static function generateSessionKey()
  {
    $key = null;
    do {
      $key = self::sha512(microtime().$_SERVER['REMOTE_ADDR']);
    } while (self::isSessionKeyExist($key));
    return $key;
  }

  /**
   * Verifie si la SessionKey existe déjà
   *
   * @param  string   $key
   * @return boolean  returns true if key already exist, false if not
   */
  public static function isSessionKeyExist($key)
  {
    $sessionSQL = new Query("loginsessions");
    return $sessionSQL->getByKey($key) != null;
  }

  /**
   * Generate a password and update the user password with it
   *
   * @param  string   $iduser
   * @return string   returns a password
   */
  public static function generateRandomPassword($email)
  {
    return substr(self::sha512(microtime().$email),0,rand(8,15));
  }

  /**
   * Creer la passwordKey et l'enregistre
   *
   * @param  string   $iduser
   * @return string   returns a PasswordKey
   */
  public static function createPasswordKey($iduser,$email)
  {
    $EM = EntityManager::getInstance();
    $passwordkey = new \Passwordkey();
    $passwordkey->key = self::generatePasswordKey($email);
    $passwordkey->userid = $iduser;
    $EM->save($passwordkey);
    return $passwordkey->key;
  }

  /**
   * Genere une password key
   *
   * @return string   returns a PasswordKey
   */
  public static function generatePasswordKey($email)
  {
    $key = null;
    do {
      $key = strtoupper(self::sha512(microtime().$email));
    } while (self::isPasswordKeyExist($key));
    return $key;
  }

  /**
   * Verifie si la PasswordKey existe déjà
   *
   * @param  string   $key
   * @return boolean  returns true if key already exist, false if not
   */
  public static function isPasswordKeyExist($key)
  {
    $passwordkeySQL = new Query("passwordkey");
    return $passwordkeySQL->getByKey($key) != null;
  }

  /**
   * Creer l'activationKey et l'enregistre
   *
   * @param  string   $iduser
   * @return string   returns an activation key
   */
  public static function createActivationKey($iduser,$email)
  {
    $EM = EntityManager::getInstance();
    $activationkey = new \Activationkey();
    $activationkey->key = self::generateActivationKey($email);
    $activationkey->userid = $iduser;
    $EM->save($activationkey,true);
    return $activationkey->key;
  }

  /**
   * Genere une activation key
   *
   * @return string   returns a ActivationKey
   */
  public static function generateActivationKey($email)
  {
    $key = null;
    do {
      $key = strtolower(self::sha512(microtime().$email));
    } while (self::isActivationKeyExist($key));
    return $key;
  }

  /**
   * Verifie si l'ActivationKey existe déjà
   *
   * @param  string   $key
   * @return boolean  returns true if key already exist, false if not
   */
  public static function isActivationKeyExist($key)
  {
    $activationkeySQL = new Query("activationkey");
    return $activationkeySQL->getByKey($key) != null;
  }


}
