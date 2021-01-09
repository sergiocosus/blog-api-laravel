<?php

namespace App\Core\Misc;

use Illuminate\Http\Request;

class MemberService
{
    public function createMember(Request $request)
    {
        $member = new Member();
        $maxMemberOrder = Member::max('order');
        $member->order = $maxMemberOrder + 1;

        return $this->updateMember($member, $request);
    }

    public function updateMember(Member $member, Request $request)
    {
        $member->fill($request->only([
            'name',
            'order',
            'description',
            'organization_id',
        ]));

        if ($request->picture) {
            $member->customMediaAdd(
                $request->picture['base64'],
                $request->picture['name']
            );
        }
        $member->save();

        return $member;
    }
}
