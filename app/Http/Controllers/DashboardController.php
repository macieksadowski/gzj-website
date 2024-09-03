<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Event;
use App\Models\EventType;
use App\Models\FinanceCategory;
use App\Models\Member;
use App\Models\Song;
use App\Models\Transaction;
use App\Services\PolishNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $menuItems = [];

    public function dashboard()
    {
        return $this->default('dashboard-sections.start', null);
    }

    public function zaiks()
    {
        $songs = Song::orderBy('title')->get();
        return $this->default('dashboard-sections.zaiks',$songs);
    }

    public function contractGenerator(Request $request)
    {
        $selectedMember = Member::where('id', '=', $request->id)->first();
        if($selectedMember == null) {
            $selectedMember = Member::where('id', '=', 11)->first();
        }
        $members = Member::all();

        return $this->default('dashboard-sections.contracts-generator',['members'=>$members,'selected'=>$selectedMember]);
    }


    public function events()
    {

        $events = Event::orderBy('date', 'desc')->get();

        $events->append('saldo');
        $events->append('contracts_amount');
        
        foreach ($events as $event) {
            $event->saldo = Transaction::where('ev_id',$event->id)->sum('amount');
            $event->contracts_amount = Contract::where('event_id',$event->id)->count();
        }

        return $this->default('dashboard-sections.wydarzenia', $events);
    }

    public function event($id)
    {
        $town  = Member::where('first_name','Maciej')->first()->last_name;
        //$response = PolishNames::getNameDeclined('Ding Ding');
        $event = Event::where('id',$id)->first();
        $sum = Transaction::where('ev_id',$id)->sum('amount');
        $members = Member::all();
        $transactions = Transaction::where('ev_id',$id)->get();
        $setlist = $event->setlistEntries()->get()->sortBy('order');
        return $this->default('dashboard-sections.wydarzenie', ['event' => $event,'transactions' => $transactions,'sum'=>$sum,'members'=>$members ,'setlist'=>$setlist]);
    }

    public function contracts()
    {

        $contracts = Contract::all();
        
        $contractsSummary = DB::table('contracts')->select(DB::raw('year(events.date) as year'), 
            'members.first_name', 'members.last_name', 
            DB::raw('count(*) as count'),
            DB::raw('SUM(contracts.contract_amount) as sum'))->
            join('events', 'events.id','=','contracts.event_id')->
            join('members', 'members.id','=','contracts.member_id')->
            groupBy('contracts.member_id', DB::raw('year(events.date)'))->
            orderBy('year','desc')->get();
        $summaryYears = array();
        foreach ($contractsSummary as $entry) {
            if(!in_array($entry->year, $summaryYears)) {
                array_push($summaryYears, $entry->year);
            }
        }

        return $this->default('dashboard-sections.contracts', ['contracts'=>$contracts,'contractsSummary'=>$contractsSummary, 'summaryYears'=>$summaryYears]);
    }



    public function default($view,$pageVariables)
    {
        $this->menuItems["Start"] = route('dashboard');
        $this->menuItems["Generator ZAiKS"] = route('zaiks');
        $this->menuItems["Umowy"] = [
            "Lista umów" => route('contracts'),
            "Generator" => route('contract-generator')
        ];
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
