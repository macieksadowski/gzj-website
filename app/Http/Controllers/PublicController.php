<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecordSet;
use App\Services\FbProxy;

class PublicController extends Controller
{
    public $menuItems = [
        "Start"=>"/",
        "O zespole" => "/o-zespole",
        "Koncerty" => "/koncerty",
        "Nagrania" => "/nagrania",
        "Oferta" => "/oferta-koncertowa.pdf\" target=\"_blank\"",
        "Rider" => [
            "Rider" => "/rider.pdf\" target=\"_blank\""

        ]

    ];

    public $socialLinks = [
        "FB"=> "https://facebook.com/glownyzaworjazzu",
        "YT"=> "https://www.youtube.com/channel/UCkOSkySgxXl_tACN8EWuOjQ",
        "SC"=> "https://soundcloud.com/glownyzaworjazzu",
        "SP"=> "https://open.spotify.com/artist/5mjGrW9DVffOeQPxSlnNoZ?autoplay=true&v=A",
        "IG"=> "https://www.instagram.com/glownyzaworjazzu/",
    ];

    public function index()
    {
        return $this->default('start', "Start.css");
    }

    public function about()
    {
        return $this->default('o-zespole', "O-zespole.css");
    }

    public function records()
    {
        $records = RecordSet::all();

        return $this->default('nagrania', "Nagrania.css",["records"=>$records]);
    }

    public function events()
    {
        $fbProxy = new FbProxy();
        try {
            $actualEvents = $fbProxy->getActualEvents();
            return $this->default('events', "Events.css",["events"=>$actualEvents]);
        } catch (\Throwable $th) {
            return back()->withErrors(['fbProxyError'=> __('fb-proxy.default')]);
        }

    }



    public function default($view, $css,$additionalArgs = [])
    {
        $args = [
            "phone"=> "+48602538140",
            "mail"=> "glownyzaworjazzu@gmail.com",
            "menuItems" => $this->menuItems,
            "socialLinks" => $this->socialLinks,
            "css" => $css
        ];

        return view($view, array_merge($args,$additionalArgs));
    }
}
