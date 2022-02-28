<?php

namespace App\Http\Controllers;


use App\Models\Contract;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function addMemberToContract(Request $request)
    {
        return back();
    }

    public function newContract(Request $request)
    {
        $contract = new Contract;
        $contract->mem_id = $request->member;
        $contract->ev_id = $request->event;
        $contract->amount = $request->amount;
        $contract->save();

        return back();
    }
}
