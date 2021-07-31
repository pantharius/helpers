<?php
namespace JDOUnivers\Helpers;

use PayPal\Exception\PayPalConnectionException;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Transaction;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Amount;
use PayPal\Api\ShippingAddress;

/**
 * Helper pour aider à l'utilisation de l'API Paypal Express Checkout
 */
class Paypal
{
    public static function getApprovalLink($returnUrl,$cancelUrl,$description,$idOrder,$itemsList,$tax=0,$reduction=0,$currency='EUR')
    {
      $apiContext = new ApiContext(new OAuthTokenCredential(getenv('PAYPALCLIENTID'),getenv('PAYPALSECRETID')));
      if(!DEV){
        $apiContext->setConfig(array('mode' => 'live'));
      }
      $payment = new Payment();

      $payment->setIntent('sale');

      $redirectUrls = new RedirectUrls();
      $redirectUrls->setReturnUrl($returnUrl);
      $redirectUrls->setCancelUrl($cancelUrl);
      $payment->setRedirectUrls($redirectUrls);

      $payer = new Payer();
      $payer->setPaymentMethod('paypal');
      $payment->setPayer($payer);

      $transaction = new Transaction();
      $transaction->setItemList($itemsList);
      $transaction->setDescription($description);
      $transaction->setAmount(self::create_amount($itemsList->getItems(),$tax,$reduction,$currency));

      
      $transaction->setCustom($idOrder);

      $payment->setTransactions([$transaction]);

      try{
        $payment->create($apiContext);
        return $payment->getApprovalLink();
      }catch(PayPalConnectionException $e){
        // TODO: Loguer $e->getData();
        return new Errors("paypal",new \Exception($e->getData()));
      }
    }

    public static function get_paiement($paiementid){
      $apiContext = new ApiContext(new OAuthTokenCredential(getenv('PAYPALCLIENTID'),getenv('PAYPALSECRETID')));
      if(!DEV){
        $apiContext->setConfig(array('mode' => 'live'));
      }
      return Payment::get($paiementid,$apiContext);
    }

    public static function approve_paiement($payment,$payerid)
    {
      $apiContext = new ApiContext(new OAuthTokenCredential(getenv('PAYPALCLIENTID'),getenv('PAYPALSECRETID')));
      if(!DEV){
        $apiContext->setConfig(array('mode' => 'live'));
      }
      $execution = new PaymentExecution();
      $execution->setPayerId($payerid);
      $execution->setTransactions($payment->getTransactions());
      
      try{
        $payment->execute($execution, $apiContext);
        return $payment;
      }catch(PayPalConnectionException $e){
        // TODO: Loguer $e->getData();
        return null;
      }
    }

    public static function getDefaultTax()
    {
      return 0.2;
    }

    public static function create_item($name,$price,$quantity=1,$currency='EUR')
    {
      $item = new Item();
      $item->setName($name);
      $item->setPrice($price);
      $item->setQuantity($quantity);
      $item->setCurrency($currency);
      return $item;
    }

    public static function create_list_item($items)
    {
      $list = new ItemList();
      foreach ($items as $item) {
        $list->addItem($item);
      }
      return $list;
    }

    public static function create_shipping_address($itemList,$city,$countryCode,$zip,$address,$address2)
    {
      $shipping_address = new ShippingAddress();
      $shipping_address->setCity($city);
      $shipping_address->setCountryCode($countryCode);
      $shipping_address->setPostalCode($zip);
      $shipping_address->setLine1($address);
      $shipping_address->setLine2($address2);
      $shipping_address->setRecipientName('Adresse de livraison');
      $itemList->setShippingAddress($shipping_address);
    }

    private static function create_amount($items,$tax,$reduction,$currency){
      $amount = new Amount();
      $subtotal = 0;
      foreach ($items as $item) {
        $subtotal += $item->getPrice() * $item->getQuantity();
      }
      $details = new Details();
      $details->setSubtotal($subtotal);
      $details->setTax($subtotal*$tax);

      $amount->setTotal($subtotal*(1+$tax));
      $amount->setCurrency($currency);
      $amount->setDetails($details);
      return $amount;
    }

}
