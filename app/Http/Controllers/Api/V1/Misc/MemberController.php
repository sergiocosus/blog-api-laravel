<?php

namespace App\Http\Controllers\Api\V1\Misc;

use App\Core\Misc\Member;
use App\Core\Misc\MemberService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\Member\CreateMemberRequest;
use App\Http\Requests\Misc\Member\DeleteMemberRequest;
use App\Http\Requests\Misc\Member\GetMemberRequest;
use App\Http\Requests\Misc\Member\UpdateMemberRequest;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * Return the members.
     */
    public function index(Request $request)
    {
        $paginated_members = Member::query()
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->with_trashed, function ($query, $with_trashed) {
                $query->withTrashed();
            })
            ->orderBy('order', 'asc')
            ->paginate($request->get('per_page', 20));

        return compact('paginated_members');
    }

    public function getOne(GetMemberRequest $request, Member $member)
    {
        $member->load();

        return compact('member');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMemberRequest $request)
    {
        $member = $this->memberService->createMember($request);

        return compact('member');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $this->memberService->updateMember($member, $request);

        return compact('member');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(DeleteMemberRequest $request, Member $member)
    {
        $member->delete();
    }

    public function restore(DeleteMemberRequest $request, $member)
    {
        $member = tap(Member::onlyTrashed()->findOrFail($member), function ($member) {
            $member->restore();
        });

        return compact('member');
    }
}
