<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create (ContactCreateRequest $request) : JsonResponse | ContactResource
    {
        $contactValidated = $request->validated();

        $userAuth = auth()->user();

        $newData = $userAuth->contacts()->create($contactValidated);

        return (new ContactResource($newData))->response()->setStatusCode(201);
    }

    private function getContact ($idContact)
    {
        $authUser = auth()->user();

        $contact = $authUser->contacts()->find($idContact);

        if (!$contact) {
            throw new HttpResponseException(response([
                'errors'    => [
                    'message'   => [
                        'resource not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $contact;
    }

    public function getDataWhere ($id) : JsonResponse | ContactResource
    {
        $contact = $this->getContact($id);
        return new ContactResource($contact);
    }

    public function update ($id, ContactUpdateRequest $request) : JsonResponse | ContactResource
    {
        $contact = $this->getContact($id);

        $data = $request->validated();

        $contact->update([
            'firstName'     => $data['firstName'],
            'lastname'      => $data['lastname'],
            'email'         => $data['email'],
            'phone'         => $data['phone']
        ]);

        return new ContactResource($contact);
    }

    public function delete ($id) : JsonResponse
    {
        $contact = $this->getContact($id);

        $contact->delete($id);

        return response()->json([
            'data'  => true
        ])->setStatusCode(200);
    }

    public function search (Request $request) : ContactCollection
    {

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $userAuth = auth()->user();

        $contact = $userAuth->contacts();

        $contact->where(function ($query) use ($request) {
            $query
            ->when($request->has('name'), function ($collection) use ($request) {
                $collection->orWhere('firstName', 'like', '%'.$request->input('name').'%')
                                  ->orWhere('lastname', 'like', '%'.$request->input('name').'%');
            })
            ->when($request->has('email'), function ($collection) use ($request) {
                $collection->where('email', 'like', '%'. $request->input('email').'%');
            })
            ->when($request->has('phone'), function ($collection) use ($request) {
                $collection->where('phone', 'like', '%'. $request->input('phone') .'%');
            });
        });

        $result = $contact->paginate(perPage : $size, page: $page);
        return new ContactCollection($result);
    }
}
