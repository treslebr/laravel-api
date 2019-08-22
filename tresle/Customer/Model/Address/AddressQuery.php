<?php


namespace Tresle\Customer\Model\Address;


use Tresle\Customer\Http\Requests\CustomerAddressRequest;

class AddressQuery
{
    /**
     * @param CustomerAddressRequest $request
     * @param $idCustomer
     * @return Address
     */
    public function createAddressByRequest(CustomerAddressRequest $request, $idCustomer){
        $address = new Address();
        $address->postcode     = $request->input("postcode");
        $address->country      = $request->input("country");
        $address->state        = $request->input("state");
        $address->city         = $request->input("city");
        $address->region       = $request->input("region");
        $address->street_1     = $request->input("street_1");
        $address->street_2     = $request->input("street_2");
        $address->street_3     = $request->input("street_3");
        $address->shipping_id  = $request->input("shipping_id");
        $address->customer_id  = (int)$idCustomer;
        $address->save();
        return $address;
    }
}
