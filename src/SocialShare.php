<?php
/**
 * Social Share Helper
 *
 */

namespace JDOUnivers\Helpers;

class SocialShare 
{
    // BALISE SCRIPT
    public static function facebook_script(){
        return "<script>
                (function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = \"//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.8&appId=1982709958624940\";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
                </script>";
    }
    public static function twitter_script(){
        return "<script>window.twttr = (function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0],
                    t = window.twttr || {};
                  if (d.getElementById(id)) return t;
                  js = d.createElement(s);
                  js.id = id;
                  js.src = \"https://platform.twitter.com/widgets.js\";
                  fjs.parentNode.insertBefore(js, fjs);

                  t._e = [];
                  t.ready = function(f) {
                    t._e.push(f);
                  };

                  return t;
                }(document, \"script\", \"twitter-wjs\"));</script>";
    }
    public static function googleplus_script(){
        return "<script src=\"https://apis.google.com/js/platform.js\" async defer>
                  {lang: 'fr'}
                </script>";
    }
    public static function pinterest_script(){
        return '<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>';
    }


    // BUTTON SHARE
    public static function facebook_button($url){
        return '<div class="fb-share-button" data-href="'.$url.'" data-layout="button" data-size="large" data-mobile-iframe="true"></div>';
    }
    public static function twitter_button($url){
        return '<a class="twitter-share-button" data-href="'.$url.'" href="https://twitter.com/intent/tweet" data-size="large"></a>';
    }
    public static function googleplus_button($url){
        return '<div class="g-plus" data-action="share" data-annotation="none" data-height="24" data-href="'.$url.'"></div>';
    }
    public static function pinterest_button($url){
        return '<a data-pin-do="buttonPin" data-pin-tall="true" data-pin-save="true" href="https://www.pinterest.com/pin/create/button/?url='.urlencode($url).'&media=&description="></a>';
    }


}