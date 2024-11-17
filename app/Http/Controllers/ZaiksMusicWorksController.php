<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class ZaiksMusicWorksController extends Controller
{
    public function index()
    {
        return view('dashboard-sections.zaiks-music-works');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        $browser = new HttpBrowser(HttpClient::create());

        $browser->request('POST', 'https://online.zaiks.org.pl/utwory-muzyczne', [
            'input-vaadin-text-field-12' => $request->input('query'),
            'g-recaptcha-response' => $request->input('g-recaptcha-response'),
        ]);

        $response = $browser->getResponse();

        // Przetwarzanie odpowiedzi
        // ...

        return view('dashboard-sections.zaiks-music-works-results', ['results' => $body]);
    }
}