<?php

namespace App\Services;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use DateTime;

class FbProxy {

    private $instance;

    public function __construct()
    {
        $this->instance = new Facebook([
            'app_id' => config('services.facebook.app_id'),
            'app_secret' => config('services.facebook.app_secret'),
            'graph_api_version' => config('services.facebook.graph_api_version'),
        ]);
    }

    public function get($getString)
    {
        try {
            $response = $this->instance->get($getString, config('services.facebook.access_token'));
        } catch (FacebookResponseException $e) {
            throw $e;
        } catch (FacebookSDKException $e) {
            throw $e;
        }

        return $response;
    }

    public function getEvents()
    {
        $response = $this->get('glownyzaworjazzu?fields=events{cover,id,name,start_time,place,ticket_uri,description,is_online}');
        $response_decoded = $response->getGraphNode()->asArray();

        return $response_decoded['events'];
    }

    public function getActualEvents()
    {
        $actualTime = new DateTime();
        $actualEvents = [];
        $events = $this->getEvents();
        foreach ($events as $event) {
            $time = $event['start_time'];
            if ($actualTime < $time) {
                array_push($actualEvents, $event);
            }
        }

        return array_reverse($actualEvents);
    }
}
