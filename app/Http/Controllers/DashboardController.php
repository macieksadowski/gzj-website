<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Event;
use App\Models\FinanceCategory;
use App\Models\Member;
use App\Models\Song;
use App\Models\Transaction;
use App\Services\PolishNames;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $menuItems = [];

    public function dashboard()
    {
        return $this->default('dashboard', null);
    }

    public function zaiks()
    {
        $songs = Song::orderBy('title')->get();
        return $this->default('zaiks',$songs);
    }

    public function contractGenerator(Request $request)
    {
        $selectedMember = Member::where('id', '=', $request->id)->first();
        if($selectedMember == null) {
            $selectedMember = Member::where('id', '=', 11)->first();
        }
        $members = Member::all();

        return $this->default('contracts-generator',['members'=>$members,'selected'=>$selectedMember]);
    }


    public function events()
    {

        $events = Event::orderBy('date')->get();
        //$categories = FinanceCategory::all();
        //$trsnsactions = Transaction::whereDate('date','>','2021-10-01')->get();
        return $this->default('wydarzenia', $events);
    }

    public function event($id)
    {
        $town  = Member::where('first_name','Maciej')->first()->last_name;
        //$response = PolishNames::getNameDeclined('Ding Ding');
        $event = Event::where('ev_id',$id)->first();
        $sum = Transaction::where('ev_id',$id)->sum('amount');
        $members = Member::all();
        $transactions = Transaction::where('ev_id',$id)->get();
        return $this->default('wydarzenie', ['event' => $event,'transactions' => $transactions,'sum'=>$sum,'members'=>$members ]);
    }

    public function contracts()
    {

        $contracts = Contract::all();
        $members = Member::all();
        $events = Event::orderBy('date')->get();
        //$categories = FinanceCategory::all();
        //$trsnsactions = Transaction::whereDate('date','>','2021-10-01')->get();
        return $this->default('contracts', ['events'=>$events,'contracts'=>$contracts,'members'=>$members ]);
    }



    public function default($view,$pageVariables)
    {
        $this->menuItems["Start"] = route('dashboard');
        $this->menuItems["Generator ZAiKS"] = route('zaiks');
        $this->menuItems["Generator Umów"] = route('contract-generator');
        $this->menuItems["Wydarzenia"] = route('eventy');
        $this->menuItems["Wyloguj się"] = route('logout');
        $paramsTable = [
            "menuItems" => $this->menuItems,
            "css" => "Dashboard.css",
        ];

        if(is_array($pageVariables)) {
            foreach ($pageVariables as $variable => $value) {
                $paramsTable[$variable] = $value;
            }
        } else {
            $paramsTable['pageVariable'] = $pageVariables;
        }

        return view($view, $paramsTable);
    }
}
