<?php

require_once 'defines.php';

require_once SITE_ROOT.'/page.php';

require_once SITE_ROOT.'/external/fbLibrary/autoload.php';

class fbProxy
{
    private $instance;
    private $app_id;
    private $app_secret;
    private $graph_api_version;
    private $access_token;

    public function __construct()
    {
        $facebookApiCredentials = page::getData(SITE_ROOT.'/data/facebookApi.json');
        $this->app_id = $facebookApiCredentials['app_id'];
        $this->app_secret = $facebookApiCredentials['app_secret'];
        $this->graph_api_version = $facebookApiCredentials['graph_api_version'];
        $this->access_token = $facebookApiCredentials['access_token'];

        $this->instance = new \Facebook\Facebook([
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'graph_api_version' => $this->graph_api_version,
        ]);
    }

    public function get($getString)
    {
        try {
            $response = $this->instance->get($getString, $this->access_token);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // Returns Graph API errors when they occur
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // Returns SDK errors when validation fails or other local issues
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