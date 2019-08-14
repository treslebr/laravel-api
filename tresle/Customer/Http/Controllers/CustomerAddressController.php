<?php


namespace Tresle\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use \Tresle\Customer\Http\Requests\CustomerAddressRequest;
use Tresle\Customer\Model\Address\Address;
use Tresle\User\Model\User;

class CustomerAddressController  extends Controller
{
    /**
     * @param CustomerAddressRequest $request
     * @param $idCustomer
     * @return array
     */
    public function store(CustomerAddressRequest $request, $idCustomer)
    {
        try {
            $customer = User::findOrFail((int)$idCustomer);

            $address = $this->createAddressByRequest($request, (int)$idCustomer);

            return ["error" => false, "message" => "", "data" => $address];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao cadastrar o endereço"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o endereço";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Cliente não encontrada" : $mensagem;
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    /**
     * @param CustomerAddressRequest $request
     * @return array
     */
    public function addAddressCustomerLogged(CustomerAddressRequest $request){
        try {
            $customerAuth = Auth::user();
            $customer = User::findOrFail($customerAuth->id);

            $address = $this->createAddressByRequest($request, $customerAuth->id);

            return ["error" => false, "message" => "", "data" => $address];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao cadastrar o endereço"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o endereço";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Cliente não encontrada" : $mensagem;
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    /**
     * @param CustomerAddressRequest $request
     * @param $idCustomer
     * @return Address
     */
    private function createAddressByRequest(CustomerAddressRequest $request, $idCustomer){
        $address = new Address();
        $address->postcode     = $request->input("postcode");
        $address->country      = $request->input("country");
        $address->state        = $request->input("state");
        $address->city         = $request->input("city");
        $address->region       = $request->input("region");
        $address->street_1     = $request->input("street_1");
        $address->street_2     = $request->input("street_2");
        $address->street_3     = $request->input("street_3");
        $address->street_4     = $request->input("street_4");
        $address->customer_id  = (int)$idCustomer;
        $address->save();
        return $address;
    }
}
