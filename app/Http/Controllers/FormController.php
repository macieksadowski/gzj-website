<?php

namespace App\Http\Controllers;


use App\Models\Contract;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function addMemberToContract(Request $request)
    {
        return back();
    }

    public function newContract(Request $request)
    {
        $validatedData = $request->validate([
            'contract' => 'required',
            'contract-amount' => 'nullable|decimal:2',
            'contract-event' => 'exists:events,id',
            'contract-person' => 'nullable|exists:members,id',
        ]);
  
        if ($validatedData['contract'] != 'no') {
            $contract = new Contract;
            $contract->contract_amount = $validatedData['contract-amount'];
            $contract->event_id = $validatedData['contract-event'];
            $member = Member::find($validatedData['contract-person']);
            $member->contracts()->save($contract);
        }

        return back();
    }

    public function updateMember(Request $request) {

        $member = Member::where('id', '=', $request->member_id)->first();

        $member->first_name = $request->data['first_name'];
        $member->last_name = $request->data['last_name'];
        $member->town = $request->data['town'];
        $member->postal_code = $request->data['postal_code'];
        $member->street = $request->data['street'];
        $member->house_no = $request->data['house_no'];
        $member->pesel = $request->data['pesel'];
        $member->birth_place = $request->data['birth_place'];
        $member->tax_office = $request->data['tax_office'];
        $member->account_no = $request->data['account_no'];

        $member->save();

        return back()->withSuccess('Pomyślnie zaktualizowano dane!');
    }
}
