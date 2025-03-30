<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    public function getAllMembers() {
        $members = Member::all();
        return response()->json($members);
    }

    public function getAllMembersNames() {
        $members = Member::all();
        $members->transform(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->display_name,
            ];
        });
        return response()->json($members);
    }
}
