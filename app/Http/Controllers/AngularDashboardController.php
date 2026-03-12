<?php

namespace App\Http\Controllers;

class AngularDashboardController extends Controller
{
    public function __invoke()
    {
        $indexPath = base_path('public_html/angular-assets/index.html');

        if (!is_file($indexPath)) {
            return view('angular');
        }

        return response()->file($indexPath, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
