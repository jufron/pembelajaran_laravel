<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use App\Http\Resources\ContactResource;
use GuzzleHttp\Promise\Each;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressController extends Controller
{
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

    private function getAddress ($contactModel, $idAddress)
    {
        $address = $contactModel->addresses()->find($idAddress);

        if (!$address) {
            throw new HttpResponseException(response([
                'errors'    => [
                    'message'   => [
                        'resource not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $address;
    }

    public function create ($id, AddressRequest $request) : JsonResponse | AddressResource
    {
        $data = $request->validated();
        $contact = $this->getContact($id);
        $newData = $contact->addresses()->create($data);
        return (new AddressResource($newData))->response()->setStatusCode(201);
    }

    private function filterAddresses($query, $request, $columns)
    {
        $columns->each(function ($column) use ($query, $request) {
            $query->when($request->has($column), function ($query) use ($request, $column) {
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            });
        });
    }

    public function get ($id, Request $request) : AddressCollection | JsonResponse
    {
        $contact = $this->getContact($id);

        $address = $contact->addresses()->where(function ($query) use ($request) {
            $this->filterAddresses($query, $request, collect([
                'street', 'rt', 'rw', 'city', 'province', 'postal_code'
            ]));
        })->paginate();
        return new AddressCollection($address);
    }

    public function find ($idContact, $idAddress) : AddressResource | JsonResponse
    {
        $contact = $this->getContact($idContact);
        $address = $this->getAddress($contact, $idAddress);
        return new AddressResource($address);
    }

    public function update ($idContact, $idAddress, AddressRequest $request) : JsonResponse | AddressResource
    {
        $data = $request->validated();
        $contact = $this->getContact($idContact);
        $address = $this->getAddress($contact, $idAddress);

        $address->update([
            'street'        => $data['street'],
            'rt'            => $data['rt'],
            'rw'            => $data['rw'],
            'city'          => $data['city'],
            'province'      => $data['province'],
            'country'       => $data['country'],
            'postal_code'   => $data['postal_code']
        ]);

        return new AddressResource($address);
    }

    public function destroy ($idContact, $idAddress) : JsonResponse
    {
        $contact = $this->getContact($idContact);
        $address = $this->getAddress($contact, $idAddress);
        $address->delete();
        return response()->json([
            'data'  => true
        ]);
    }
}
