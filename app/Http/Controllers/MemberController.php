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

    public function createMember(Request $request) {
        $validatedData = $this->validateMemberData($request);
        $member = Member::create($validatedData);
        return response()->json($member, 201);
    }

    public function editMember(Request $request, $id) {
        $member = Member::findOrFail($id);
        $validatedData = $this->validateMemberData($request);

        $member->update($validatedData);
        return response()->json($member);
    }

    private function validateMemberData($data) {
        return $data->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:10',
            'postal_code' => 'nullable|string|max:10',
            'town' => 'nullable|string|max:255',
            'pesel' => 'nullable|digits:11',
            'birth_place' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:34',
            'tax_office' => 'nullable|string|max:255',
        ]);
    }
}
