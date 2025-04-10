<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\EnumType;
use App\Models\EventType;
use App\Models\Member;
use App\Models\Event;
use App\Models\Setlist;
use App\Models\SetlistEntry;
use App\Models\Song;
use EnumTypeDiscriminator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    private  $dashboardCtrl;

    public function __construct() {
        $this->dashboardCtrl = new DashboardController();
    }

    public function index() {
        $events = Event::orderBy('date', 'desc')->get();
        //$categories = FinanceCategory::all();
        //$trsnsactions = Transaction::whereDate('date','>','2021-10-01')->get();
        return $this->dashboardCtrl->default('dashboard-sections.wydarzenia', $events);
    }

    public function searchEvents(Request $request) {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $searchQuery = $request->input('search', '');

        $events = Event::where('name', 'LIKE', '%' . $searchQuery . '%')
            ->orderBy('date', 'desc') 
            ->take(10) 
            ->get(['id', 'name', 'date'])
            ->transform(function ($event) {
                $event->name = $event->name . ' - ' . date('d.m.Y', strtotime($event->date));
                return $event;
            });

        return response()->json($events);
    }

    public function getAllEvents() {
        $events = Event::orderBy('date', 'desc')->get();
        $events->transform(function ($event) {
            $event->saldo = Transaction::where('ev_id',$event->id)->sum('amount');
            $event->contracts_amount = Contract::where('event_id',$event->id)->count();
            $event->type = $event->type->value;
            return $event;
        });
        return response()->json($events);
    }

    public function getEventTypes() {
        $eventTypes = EnumType::where('discriminator', EnumTypeDiscriminator::EVENT_TYPE)->get();
        return response()->json($eventTypes);
    }

    public function getEvent($id) {
        $event = Event::find($id);
        $event->saldo = Transaction::where('ev_id',$event->id)->sum('amount');
        $transactions = Transaction::where('ev_id',$id)->get();
        $transactions->transform(function ($transaction) {
            $transaction->id = $transaction->tr_id;
            unset($transaction->tr_id);

            $transaction->category = $transaction->category->name;
            return $transaction;
        });
        $event->transactions = $transactions;

        $contracts = Contract::where('event_id',$id)->get();
        $contracts->transform(function ($contract) {
            $contract->member = $contract->member->value;
            $contract->type = $contract->type->value;
            return $contract;
        });
        $event->contracts = $contracts;

        $event->type = $event->type->value;
        return response()->json($event);
    }

    public function createEvent(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required',
            'type' => 'required|numeric',
            'date' => 'required|date|unique:events,date',
        ]);

        $event = new Event();

        $event->name = $validatedData['name'];
        $event->date = $validatedData['date'];
        $event->type()->associate(EnumType::find($validatedData['type']));

        $event->save();
        return response()->json($event);

    }

    public function editEvent(Request $request, $id) {
        $event = Event::find($id);
        $event->name = $request->input('name');
        $event->date = $request->input('date');
        $event->type()->associate(EnumType::find($request->input('type')));
        $event->save();
        return response()->json($event);
    }

    public function deleteEvent($id) {
        $event = Event::find($id);
        $event->delete();
        return response()->json(['message' => "Event with id: $id deleted"]);
    }

    public function getAllContracts() {
        $contracts = Contract::all();
        $contracts->transform(function ($contract) {
            return [
                'id' => $contract->id,
                'contract_amount' => $contract->contract_amount,
                'member' => [
                    'name' => $contract->member->first_name . ' ' . $contract->member->last_name,
                    'id' => $contract->member->id
                ],
                'type' => [
                    'id' => $contract->type->id,
                    'value' => $contract->type->value
                ],
                'event' => [
                    'name' => $contract->event->name,
                    'id' => $contract->event->id,
                    'date' => $contract->event->date,
                ]
            ];
        });
        return response()->json($contracts);
    }

    public function getContractsSummaryPerYear() {
        $contracts = Contract::all();
        
        $contractsSummary = DB::table('contracts')->select(DB::raw('year(events.date) as year'), 
            'members.first_name', 'members.last_name', 
            DB::raw('count(*) as count'),
            DB::raw('SUM(contracts.contract_amount) as sum'))->
            join('events', 'events.id','=','contracts.event_id')->
            join('members', 'members.id','=','contracts.member_id')->
            groupBy('contracts.member_id', DB::raw('year(events.date)'))->
            orderBy('year','desc')->get()->groupBy('year');

        return response()->json($contractsSummary);
    }

    /**
     * Show the step One Form for creating a new event.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStepOne(Request $request)
    {
        $event = $request->session()->get('event');
        $eventTypes = EnumType::where('discriminator', EnumTypeDiscriminator::EVENT_TYPE)->get();
  
        return $this->dashboardCtrl->default('dashboard-sections.new-event.create-step-one', compact('event', 'eventTypes'));
    }
    /**  
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreateStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'event-name' => 'required',
            'event-type' => 'required|numeric',
            'event-date' => 'required|date|unique:events,date',
        ]);
  
        if(empty($request->session()->get('event'))){
            $event = new Event();
        }else{
            $event = $request->session()->get('event');
        }
        $this->fillEvent($event, $validatedData);
        $evType = EnumType::find($validatedData['event-type']);

        $request->session()->put('event', $event);
        $request->session()->put('event-type', $evType);
  
        return redirect()->route('events.new.step.two');
    }

    private function fillEvent($event, $validatedData) {
            $event->name = $validatedData['event-name'];
            $event->date = $validatedData['event-date'];
    }
  
    /**
     * Show the step Two Form for creating a new event.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStepTwo(Request $request)
    {
        $event = $request->session()->get('event');
        $members = Member::all();
  
        return $this->dashboardCtrl->default('dashboard-sections.new-event.create-step-two', compact('event', 'members'));
    }
  
    /**
     * Post Request to store step2 info in session
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreateStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'contract' => 'required',
            'contract-amount' => 'nullable|decimal:2',
            'contract-person' => 'nullable',
        ]);
  
        if ($validatedData['contract'] != 'no') {
            $event = $request->session()->get('event');
            if (empty($event->contract)) {
                $contract = new Contract;
                $this->fillContract($contract, $validatedData);
                $event->contract()->save($contract);
            } else {
            $this->fillContract($event->contract, $validatedData);
            }
            $request->session()->put('event', $event);
        }        
  
        return redirect()->route('events.new.step.three');
    }

    private function fillContract($contract, $validatedData) {
        $contract->amount = $validatedData['contract-amount'];
        $contract->member()->save(Member::find($validatedData['contract-person']));
    }
  
    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStepThree(Request $request)
    {
        $event = $request->session()->get('event');
        $songs = Song::all();
  
        return $this->dashboardCtrl->default('dashboard-sections.new-event.create-step-three', compact('event', 'songs'));
    }
  
    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreateStepThree(Request $request)
    {
        $event = $request->session()->get('event');

        if($request->input('songs') != null) {
            $songs = Song::whereIn('id', $request->input('songs'))->get()->all();

            $this->createSetlist($songs, $event);
        }
        $event->save();
        $event->evType()->save($request->session()->get('event-type'));
  
        $request->session()->forget('event');
        $request->session()->forget('event-type');
  
        return redirect()->route('events.index');
    }

    private function createSetlist(array $songs, Event $event) {
        $entries = array();
        foreach ($songs as $key => $song) {
            $entry = new SetlistEntry;
            $entry->order = $key;
            $entry->song_id = $song->id;
            $entry->event_id = $event->id;
            array_push($entries, $entry);
        }
        $event->setlistEntries()->saveMany($entries);
    }


    public function postEditEvent(Request $request) {
        switch($request->input('action')) {
            case 'editContracts':
                return $this->postEditContracts($request);
            case 'editSummary':
                return $this->postEditSummary($request); 
            case 'delete':
                return $this->postDelete($request);       
        }
    }

    private function postDelete(Request $request) {
        $validatedData = $request->validate([
            'id' => 'exists:App\Models\Event,id',
        ]);
        $name =Event::find($validatedData['id'])->name;

        Event::destroy($validatedData['id']);

        return redirect()->withSuccess('Wydarzenie '.$name.' zostało usunięte')->route('events.index'); 
    }

    private function postEditSummary(Request $request) {
        $validatedData = $request->validate([
            'event' => 'exists:App\Models\Event,id',
            'event-name' => 'required',
            'event-date' => Rule::unique('events','date')->ignore($request->get('event')),
            'event-type' => 'exists:App\Models\EnumType,id',
        ]);

        $event = Event::find($validatedData['event']);

        $event->name = $validatedData['event-name'];
        $event->date = $validatedData['event-date'];
        $event->type()->associate(EnumType::find($validatedData['event-type']));
        $event->save();

        return back()->withSuccess('Pomyślnie zaktualizowano dane!');
    }

    private function postEditContracts(Request $request) {
        $validatedData = $request->validate([
            'event' => 'exists:App\Models\Event,id',
            'new-contract.*.contract-person' => 'nullable|exists:App\Models\Member,id',
            'new-contract.*.contract-amount' => 'nullable|decimal:2',
            'new-contract.*.contract-type' => 'nullable|exists:App\Models\EnumType,id',
            'deletedContracts' => 'required_without:new-contract|exists:App\Models\Event,id'
        ]);

        if(isset($validatedData['deletedContracts'])) {
            Contract::destroy($validatedData['deletedContracts']);
        }

        if(isset($validatedData['new-contract'])) {
            $event = Event::find($validatedData['event']);
           
            foreach ($validatedData['new-contract'] as $newContract) {
                $contract = new Contract;
                $contract->contract_amount = $newContract['contract-amount'];
                $contract->member()->associate(Member::find($newContract['contract-person']));    
                $contract->type()->associate(EnumType::find($newContract['contract-type']));
                $event->contracts()->save($contract);
            }
        }

        return back()->withSuccess('Pomyślnie zaktualizowano dane!');
    }
}
