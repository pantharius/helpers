<?php

namespace JDOUnivers\Helpers;

class Youtube{
    public static function getVideoByPlaylistId($playListId, $maxResult = 25){
        $client = new \Google_Client();
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setHttpClient($guzzleClient);

        $client->setApplicationName(getenv('YOUTUBEAPPNAME'));
        $client->setDeveloperKey(getenv('YOUTUBEAPIKEY'));
        $youtubeService = new \Google_Service_YouTube($client);

        $response = $youtubeService->playlistItems->listPlaylistItems('snippet,contentDetails', ['maxResults' => $maxResult, 'playlistId' => $playListId]);

        return $response->items;
    }
}