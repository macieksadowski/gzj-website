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
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
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
        $contractsAgg = Contract::query()
            ->select('event_id', DB::raw('COUNT(*) as contracts_amount'))
            ->groupBy('event_id');

        $transactionsAgg = Transaction::query()
            ->select('ev_id', DB::raw('SUM(amount) as saldo'))
            ->groupBy('ev_id');

        $events = DB::table('events as e')
            ->leftJoinSub($contractsAgg, 'contracts_agg', function ($join) {
                $join->on('contracts_agg.event_id', '=', 'e.id');
            })
            ->leftJoinSub($transactionsAgg, 'transactions_agg', function ($join) {
                $join->on('transactions_agg.ev_id', '=', 'e.id');
            })
            ->leftJoin('enum_types as et', 'et.id', '=', 'e.type_id')
            ->select([
                'e.id',
                'e.name',
                'e.date',
                'e.type_id',
                'et.id as type_id',
                'et.value as type_value',
                DB::raw('COALESCE(contracts_agg.contracts_amount, 0) as contracts_amount'),
                DB::raw('COALESCE(transactions_agg.saldo, 0) as saldo'),
            ])
            ->orderBy('e.date', 'desc')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => (int) $event->id,
                    'name' => $event->name,
                    'date' => $event->date,
                    'type_id' => $event->type_id,
                    'type' => [
                        'id' => $event->type_id,
                        'value' => $event->type_value,
                    ],
                    'contracts_amount' => (int) $event->contracts_amount,
                    'saldo' => (float) $event->saldo,
                ];
            });

        return response()->json($events->values());
    }

    public function getAllEventIds() {
        $eventIds = Event::query()
            ->select(['id'])
            ->orderBy('date', 'desc')
            ->pluck('id');

        return response()->json($eventIds);
    }

    public function getEventTypes() {
        $eventTypes = EnumType::where('discriminator', EnumTypeDiscriminator::EVENT_TYPE)->get();
        return response()->json($eventTypes);
    }

    public function getContractTypes() {
        $contractTypes = EnumType::where('discriminator', EnumTypeDiscriminator::CONTRACT_TYPE)->get();
        return response()->json($contractTypes);
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
            return [
                'id' => $contract->id,
                'contract_amount' => $contract->contract_amount,
                'member' => [
                    'name' => $contract->member->first_name . ' ' . $contract->member->last_name,
                    'display_name' => $contract->member->display_name,
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
        $event->contracts = $contracts;

        $setlist = $event->setlistEntries()->with('song')->orderBy('order')->get();
        $setlist->transform(function ($entry) {
            return [
                'id' => $entry->id,
                'order' => $entry->order,
                'song' => [
                    'id' => $entry->song?->id,
                    'title' => $entry->song?->title,
                ],
            ];
        });
        $event->setlist = $setlist;

        $event->type = $event->type->value;
        return response()->json($event);
    }

    public function updateEventSetlist(Request $request, $id) {
        $validatedData = $request->validate([
            'song_ids' => 'required|array',
            'song_ids.*' => 'integer|exists:App\Models\Song,id',
        ]);

        try {
            DB::transaction(function () use ($id, $validatedData) {
                $event = Event::findOrFail($id);
                $event->setlistEntries()->delete();

                $entries = [];
                foreach ($validatedData['song_ids'] as $index => $songId) {
                    $entries[] = new SetlistEntry([
                        'order' => $index,
                    ]);
                    $entries[$index]->song_id = $songId;
                }

                if (!empty($entries)) {
                    $event->setlistEntries()->saveMany($entries);
                }
            });

            return response()->json(['message' => 'Setlista zaktualizowana pomyślnie.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update setlist', 'details' => $e->getMessage()], 500);
        }
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

    public function updateEventContracts(Request $request, $id) {
        // Accept same payload as the Blade form handler: event, new-contract.*, deletedContracts
        $validatedData = $request->validate([
            'event' => 'required|exists:App\Models\Event,id',
            'new-contract' => 'sometimes|array',
            'new-contract.*.contract-person' => 'nullable|exists:App\Models\Member,id',
            'new-contract.*.contract-amount' => 'nullable|decimal:2',
            'new-contract.*.contract-type' => 'nullable|exists:App\Models\EnumType,id',
            'deletedContracts' => 'sometimes|required_without:new-contract|array',
            'deletedContracts.*' => 'integer|exists:App\Models\Contract,id'
        ]);

        try {
            if(isset($validatedData['deletedContracts'])) {
                Contract::destroy($validatedData['deletedContracts']);
            }

            if(isset($validatedData['new-contract'])) {
                $event = Event::find($id);
               
                foreach ($validatedData['new-contract'] as $newContract) {
                    $contract = new Contract;
                    $contract->contract_amount = $newContract['contract-amount'];
                    $contract->member()->associate(Member::find($newContract['contract-person']));    
                    $contract->type()->associate(EnumType::find($newContract['contract-type']));
                    $event->contracts()->save($contract);
                }
            }

            return response()->json(['message' => 'Pomyślnie zaktualizowano dane!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update contracts', 'details' => $e->getMessage()], 500);
        }
    }
}
