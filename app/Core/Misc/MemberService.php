<?php

namespace App\Core\Misc;

use Illuminate\Http\Request;

class MemberService
{
    public function createMember(Request $request)
    {
        $post = new Member();

        return $this->updateMember($post, $request);
    }

    public function updateMember(Member $member, Request $request)
    {
        $member->fill($request->only([
            'name',
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
