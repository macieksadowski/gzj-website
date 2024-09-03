<?php

namespace App\Http\Controllers;

use App\Models\Song;

class SongsController extends Controller {

    private $dashboardCtrl;

    public function __construct() {
        $this->dashboardCtrl = new DashboardController();
    }

    public function index() {
        $songs = Song::orderBy('title', 'asc')->get();
        return $this->dashboardCtrl->default('dashboard-sections.songs', $songs);
    }

    

}