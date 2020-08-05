<?php

namespace App\Http\Controllers\Api\V1\Misc;

use App\Core\Misc\ContactResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\ContactResponse\DeleteContactRequest;
use App\Http\Requests\Misc\ContactResponse\GetContactRequest;
use Illuminate\Http\Request;

class ContactResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(GetContactRequest $request)
    {
        $contact_responses = ContactResponse::query()
            ->when($request->with_trashed === 'true', function ($query, $with_trashed) {
                $query->withTrashed();
            })->get();

        return compact('contact_responses');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $contact_response = new ContactResponse();
        $contact_response->fill($request->all())->save();

        return compact('contact_response');
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(DeleteContactRequest $request, ContactResponse $contactResponse)
    {
        $contactResponse->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function restore(DeleteContactRequest $request, $contact_response)
    {
        $contact_response = ContactResponse::onlyTrashed()->find($contact_response);
        $contact_response->restore();

        return compact('contact_response');
    }
}
