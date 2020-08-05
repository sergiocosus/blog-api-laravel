<?php

namespace App\Http\Controllers\Api\V1\Misc;

use App\Core\Misc\Organization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\Organization\CreateOrganizationRequest;
use App\Http\Requests\Misc\Organization\DeleteOrganizationRequest;
use App\Http\Requests\Misc\Organization\GetOrganizationRequest;
use App\Http\Requests\Misc\Organization\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(GetOrganizationRequest $request)
    {
        $organizations = Organization::get();

        return compact('organizations');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CreateOrganizationRequest $request)
    {
        $organization = new Organization();
        $organization->fill($request->all())->save();

        return compact('organization');
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(GetOrganizationRequest $request, Organization $organization)
    {
        return compact('organization');
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $organization->fill($request->all())->save();

        return compact('organization');
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(DeleteOrganizationRequest $request, Organization $organization)
    {
        $organization->delete();
    }
}
