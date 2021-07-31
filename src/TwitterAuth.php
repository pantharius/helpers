<?php
/**
 * TwitterAuth class
 */

namespace JDOUnivers\Helpers;

use Codebird\Codebird;

class TwitterAuth
{
    private $client;
    private $clientCB;

    public function __construct(){
        Codebird::setConsumerKey(getenv("TWITTER_KEY"), getenv("TWITTER_SECRETKEY"));
        $this->client = Codebird::getInstance();
        $this->clientCB = getenv("TWITTER_CALLBACKURL");
    }

    public function getAuthUrl()
    {
        $this->requestTokens();
        $this->verifyTokens();
        return $this->client->oauth_authenticate();
    }

    public function isSignedIn()
    {
        return isset($_SESSION["user_id"]);
    }

    public function signIn()
    {
        if($this->hasCallback()) {
            $this->verifyTokens();

            $reply = $this->client->oauth_accessToken([
                'oauth_verifier' => $_GET["oauth_verifier"]
            ]);
            
            if($reply->httpstatus == 200){

                $this->storeTokens($reply->oauth_token, $reply->oauth_token_secret);
                $this->verifyTokens();

                $_SESSION["user_id"] = $reply->user_id;

                $userinfos = $this->client->account_verifyCredentials(array('include_entities' => 'true', 'skip_status' => 'true', 'include_email' => 'true'));

                return array(
                    "username" => $reply->screen_name,
                    "twitterid" => $reply->user_id,
                    "email" => $userinfos->email
                );
            }
        }

        return false;
    }

    public function hasCallback()
    {
        return (isset($_GET["oauth_verifier"]));
    }

    public function publishStatus($status){
        $this->verifyTokens();
        $reply = $this->client->statuses_update('status=' . urlencode($status));
    }

    protected function requestTokens()
    {
        $reply = $this->client->oauth_requestToken([
            'oauth_callback' => $this->clientCB
        ]);
        $this->storeTokens($reply->oauth_token, $reply->oauth_token_secret);
    }

    protected function storeTokens($token, $tokenSecret)
    {
        $_SESSION['oauth_token'] = $token;
        $_SESSION['oauth_token_secret'] = $tokenSecret;
    }

    protected function verifyTokens()
    {
        $this->client->setToken($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
    }

}
