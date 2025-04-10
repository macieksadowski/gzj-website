<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use DateTime;
use Illuminate\Support\Facades\Log;

/**
 * This class is responsible for making requests to the Facebook Graph API.
 */
class FbRequestService {

    private $client;
    private $graphApiVersion;
    private $accessToken;

    /** The request string for the events node of the Facebook Graph API */
    private $eventsRequest = 'fields=events{cover,id,name,start_time,place,ticket_uri,description,is_online}';
    
    public function __construct()
    {
        $this->client = new PendingRequest();
        $this->graphApiVersion =  config('services.facebook.graph_api_version');
        $this->accessToken = config('services.facebook.access_token');

    }

    /** 
     * This function makes a request to the Facebook Graph API and returns all events of the band.
     * @return array Array of events
     */
    public function getEvents()
    {
        $response = Http::withUrlParameters([
            'endpoint' => 'https://graph.facebook.com',
            'page' => 'glownyzaworjazzu',
            'version' => $this->graphApiVersion,
            'fields' => 'events{cover,id,name,start_time,place,ticket_uri,description,is_online}',  
            'access_token' => $this->accessToken
        ])->get('{+endpoint}/{version}/{page}?fields={fields}&access_token={access_token}');
        
        $response_decoded = $response->json();
        if($response->getStatusCode() != 200) {
            Log::error('FB Graph API Response '.$response->getStatusCode().' : '.$response->getReasonPhrase());
        }

        if(!array_key_exists('events', $response_decoded)) {
            Log::error('FB Graph API has not returned node \'events\'!');
        }

        return $response_decoded['events']['data'];
    }

    /** 
     * This function returns all events that have not yet taken place.
     * @return array Array of events, ordered by date in descending order
     */
    public function getActualEvents()
    {
        $actualTime = new DateTime();
        $actualEvents = [];
        $events = $this->getEvents();
        foreach ($events as $event) {
            $time = DateTime::createFromFormat(DateTime::ISO8601, $event['start_time']);
            $event['start_time'] = $time;
            if ($actualTime < $time) {
                array_push($actualEvents, $event);
            }
        }

        return array_reverse($actualEvents);
    }
}