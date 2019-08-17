<?php


namespace Tresle\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Tresle\Customer\Http\Requests\CustomerAddressRequest;
use Tresle\Customer\Model\Address\Address;
use Tresle\Customer\Model\Customer\Customer;
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
        $address->shipping_id  = $request->input("shipping_id");
        $address->customer_id  = (int)$idCustomer;
        $address->save();
        return $address;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $idCustomer
     * @param $idAddress
     * @return array
     */
    public function destroy(\Illuminate\Http\Request $request, $idCustomer, $idAddress)
    {
        return $this->delete($idCustomer, $idAddress);
    }

    /**
     * @param CustomerAddressRequest $request
     * @param $idCustomer
     * @param $idAddress
     * @return array
     */
    public function update(CustomerAddressRequest $request, $idCustomer, $idAddress)
    {
        return $this->updateAction($request, $idCustomer, $idAddress);
    }

    /**
     * @param CustomerAddressRequest $request
     * @param $idAddress
     * @return array
     */
    public function updateAddressCustomerLogged(CustomerAddressRequest $request, $idAddress)
    {
        $customerAuth = Auth::user();
        return $this->updateAction($request, $customerAuth->id, $idAddress);
    }

    /**
     * @param CustomerAddressRequest $request
     * @param $idCustomer
     * @param $idAddress
     * @return array
     */
    public function updateAction(CustomerAddressRequest $request, $idCustomer, $idAddress)
    {
        try {
            $address = Address::where('id', (int)$idAddress)
                ->where('customer_id', (int)$idCustomer)
                ->firstOrFail();;

            $data = $request->all();
            $address->update($data);
            return ["error" => false, "message" => Address::where('id', (int)$idAddress)->get()];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Endereço não encontrado"];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $idAddress
     * @return array
     */
    public function deleteAddressCustomerLogged(\Illuminate\Http\Request $request, $idAddress)
    {
        $customerAuth = Auth::user();
        return $this->delete($customerAuth->id, $idAddress);
    }

    /**
     * @param $idCustomer
     * @param $idAddress
     * @return array
     */
    private function delete($idCustomer, $idAddress){
        try {
            $address = Address::where('customer_id', (int)$idCustomer)
                ->where('id', (int)$idAddress)
                ->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Categoria não encontrada"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir o endereço";
            return ["error" => true, "message" => $mensagem];
        }
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getCustomerLogged(Request $request)
    {
        return Customer::with('addresses.shipping')
            ->find(Auth::id());
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function show(Request $request, $id)
    {
        try {
            $customer = Customer::with('addresses.shipping')->findOrFail((int)$id);
            return ["error" => false, "message" => "", "data" => $customer];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Cliente não encontrado"];
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Customer[]
     */
    public function index()
    {
        return Customer::with(['addresses', 'shipping'])->get();
    }
}
