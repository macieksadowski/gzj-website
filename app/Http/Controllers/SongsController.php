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

    public function getAllSongs() {
        $songs = Song::orderBy('title', 'asc')->get();
        return response()->json($songs);
    }

    public function getSong($id) {
        $song = Song::findOrFail($id);
        return response()->json($song);
    }

    public function createSong() {
        $data = request()->validate([
            'title' => 'required|string|max:255',
            'title_official' => 'nullable|string|max:255',
            'performer' => 'required|string|max:255',
            'composer' => 'required|string|max:255',
            'text_author' => 'required|string|max:255',
        ]);
        $song = new Song();
        $this->fillSong($song, $data);

        $song->save();

        return response()->json($song);
    }

    public function editSong($id) {
        $data = request()->validate([
            'title' => 'required|string|max:255',
            'title_official' => 'nullable|string|max:255',
            'performer' => 'required|string|max:255',
            'composer' => 'required|string|max:255',
            'text_author' => 'required|string|max:255',
        ]);
        $song = Song::findOrFail($id);
        $this->fillSong($song, $data);

        $song->save();

        return response()->json($song);
    }

    private function fillSong($song, $data) {
        $song->title = $data['title'];
        $title_official = '';
        if (isset($data['title_official'])) {
            $title_official = $data['title_official'];
        } else {
            $title_official = $data['title'];
        }
        $song->title_official = $title_official;
        $song->performer = $data['performer'];
        $song->composer = $data['composer'];
        $song->text_author = $data['text_author'];

        return $song;
    }

    public function deleteSong($id) {
        $song = Song::findOrFail($id);
        $song->delete();
        return response()->json(['message' => 'Song with id ' . $id . ' deleted successfully.']);
    }

    

}