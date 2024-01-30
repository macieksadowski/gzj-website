<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecordLink;
use App\Models\RecordSet;
use App\Models\Style;
use App\Services\FbRequestService;

class PublicController extends Controller
{
    public $menuItems = [
        "Start"=>"#hero",
        "O zespole" => "#about",
        "Koncerty" => "#events",
        "Nagrania" => "#records",
        "Dyskografia" => "#albums",
        "Kontakt" => "#contact",
        "Oferta" => "/oferta-koncertowa.pdf",
        "Do pobrania" => [
            "Presspack" => "/presspack",
            "Rider" => "/rider.pdf"
        ],
    ];

    public $socialLinks = [
        "FB"=> "https://facebook.com/glownyzaworjazzu",
        "YT"=> "https://www.youtube.com/channel/UCkOSkySgxXl_tACN8EWuOjQ",
        "SC"=> "https://soundcloud.com/glownyzaworjazzu",
        "SP"=> "https://open.spotify.com/artist/5mjGrW9DVffOeQPxSlnNoZ?autoplay=true&v=A",
        "IG"=> "https://www.instagram.com/glownyzaworjazzu/",
        "TK"=> "https://www.tiktok.com/@glownyzaworjazzu"
    ];

    public function index()
    {
        $fbService = new FbRequestService();

        //* EVENTS */
        try {
            $actualEvents = $fbService->getActualEvents();
        } catch (\Throwable $th) {
            return $this->withErrors('layouts.public', "", ['fbProxyError'=> __('fb-proxy.default')]);
        }
        //* STYLES */
        $styles = Style::all();
        //* YOUTUBE RECORDS */
        $maxNumberOfRecordsInSplide = 8;

        $unhighlightedRecords = RecordLink::where('highlighted', false)->get()->toArray();
        $highlightedRecords = RecordLink::where('highlighted', true)->get()->toArray();
        $selectedRecords = [];
        $selectedRecords = array_merge($selectedRecords, $highlightedRecords);
        shuffle($unhighlightedRecords);
        $selectedRandomRecords = array_slice($unhighlightedRecords, 0, $maxNumberOfRecordsInSplide - count($highlightedRecords));
        $selectedRecords = array_merge($selectedRecords, $selectedRandomRecords);


        return $this->default('layouts.public', "", ["events"=>$actualEvents, "styles"=>$styles, "records"=>$selectedRecords]);

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
        $fbService = new FbRequestService();
        try {
            $actualEvents = $fbService->getActualEvents();
            return $this->default('events', "Events.css",["events"=>$actualEvents]);
        } catch (\Throwable $th) {
            return back()->withErrors(['fbProxyError'=> __('fb-proxy.default')]);
        }

    }



    public function default($view, $css,$additionalArgs = [])
    {
        setlocale(LC_ALL, 'pl_PL.UTF-8');
        $args = [
            "phone"=> "+48602538140",
            "mail"=> "glownyzaworjazzu@gmail.com",
            "menuItems" => $this->menuItems,
            "socialLinks" => $this->socialLinks,
            "css" => $css
        ];

        return view($view, array_merge($args,$additionalArgs));
    }

    public function withErrors($view, $errors, $additionalArgs = []) {
        setlocale(LC_ALL, 'pl_PL.UTF-8');
        $args = [
            "phone"=> "+48602538140",
            "mail"=> "glownyzaworjazzu@gmail.com",
            "menuItems" => $this->menuItems,
            "socialLinks" => $this->socialLinks
        ];

        return view($view, array_merge($args,$additionalArgs))->withErrors($errors);
    }
}
