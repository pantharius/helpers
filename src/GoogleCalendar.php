<?php

namespace JDOUnivers\Helpers;

class GoogleCalendar
{
    private static $client;
    private static $service;

    public static function GetCalendarEvents($startDate, $endDate, $calendarID="primary")
    {
        $s = self::GetService();
        $optParams = array("timeMin" => $startDate, "timeMax" => $endDate);
        $events = $s->events->listEvents($calendarID, $optParams);
        $listevents = array();

        while(true) {
            foreach ($events->getItems() as $event) {
                $listevents[] = $event;
            }
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams["pageToken"] = $pageToken;
                $events = $s->events->listEvents($calendarID, $optParams);
            } else {
                break;
            }
        }
        return $listevents;
    }

    public static function GetEventDetails($eventID,$calendarID="primary")
    {
        $s = self::GetService();
        return $s->events->get($calendarID, $eventID);
    }

    private static function GetService(){
        if(self::$service == null) self::$service = new \Google_Service_Calendar(self::GetClient());
        return self::$service;
    }

    private static function GetClient(){
        if(self::$client == null){
            self::$client = new \Google_Client();
            self::$client->setAuthConfig(json_decode(GOOGLECALENBDARCREDENTIALS, true));
            self::$client->setScopes(array("https://www.googleapis.com/auth/calendar"));
        }
        return self::$client;
    }
}
