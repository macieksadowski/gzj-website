<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecordLink;
use App\Models\RecordSet;
use App\Models\Style;
use App\Services\FbRequestService;

class PublicController extends Controller
{
    /**
     * The menu items of the navigation bar. The key is the name of the item, the value is the anchor to the section.
     * If the value is an array, it means that the item has a dropdown menu. The key is the name of the dropdown item, the value is the anchor to the section.
     * @var array
     */
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

    /**
     * The links to the social media of the band, used in the navigation bar and in the footer
     * @var array
     */
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

    /**
     * Renders the view with the information about the band
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return $this->default('o-zespole', "O-zespole.css");
    }

    /** 
     * Renders the view with all records of the band
     * @return \Illuminate\View\View
     */
    public function records()
    {
        $records = RecordSet::all();

        return $this->default('nagrania', "Nagrania.css",["records"=>$records]);
    }

    /**
     * Renders the view with all upcoming events from the Facebook page of the band
     * @return \Illuminate\View\View
     */      
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



    /**
     * This method is used to render the view with the default layout
     * @param string $view The name of the view
     * @param string $css The name of the additional css file
     * @param array $additionalArgs Additional arguments to pass to the view
     * @return \Illuminate\View\View
     */
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

    /**
     * This method is used to render the view with the default layout and errors in a modal dialog
     * @param string $view The name of the view
     * @param array $errors The errors to display
     * @param array $additionalArgs Additional arguments to pass to the view
     * @return \Illuminate\View\View
     */
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
