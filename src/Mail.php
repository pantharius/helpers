<?php
namespace JDOUnivers\Helpers;

use JDOUnivers\Helpers\DB\EntityManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mail
{
  public static function send_mail($toName,$toMail,$fromName,$fromMail,$subject,$message,$priority=3){
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try{
      self::send_mail_log($toName,$toMail,$fromName,$fromMail,$subject,$message->get_html(),$priority);

      //Encodage settings
      $mail->CharSet = 'utf-8';

      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_OFF;
      $mail->isSMTP();
      $mail->Host = getenv('SMTPHOST');
      $mail->Port = getenv('SMTPPORT');
      $mail->SMTPAuth = true;
      $mail->Username = getenv('SMTPUSER');
      $mail->Password = getenv('SMTPPASSWORD');
      $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
      $mail->Priority = $priority;

      //Recipients
      $mail->setFrom($fromMail, $fromName);
      $mail->addAddress($toMail,$toName);
      $mail->addBCC(getenv('SMTPUSER'),"Log Message Site");

      //Content
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message->get_html();
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      return $mail->send();
    } catch (Exception $e) {
      self::send_mail_log($toName,$toMail,$fromName,$fromMail,$subject,"<pre>".Exceptions::tojson($e).$mail->ErrorInfo."</pre>",$priority);
      return false;
    } catch (\Exception $e) {
      self::send_mail_log($toName,$toMail,$fromName,$fromMail,$subject,"<pre>".Exceptions::tojson($e)."</pre>",$priority);
      return false;
    }
  }

  private static function send_mail_log($toName,$toMail,$fromName,$fromMail,$subject,$messageHtml,$priority){
    $EM = EntityManager::getInstance();
    $mailLog = new \Maillog();
    $mailLog->toname = $toName;
    $mailLog->tomail = $toMail;
    $mailLog->fromname = $fromName;
    $mailLog->frommail = $fromMail;
    $mailLog->subject = $subject;
    $mailLog->message = $messageHtml;
    $mailLog->priority = $priority;
    $mailLog->sentdate = Date::NowToString();
    $EM->save($mailLog);
  }
}
