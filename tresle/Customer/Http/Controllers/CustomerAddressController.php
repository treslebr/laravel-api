<?php


namespace Tresle\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Tresle\Customer\Http\Requests\CustomerAddressRequest;
use Tresle\Customer\Model\Address\Address;
use Tresle\Customer\Model\Address\AddressQuery;
use Tresle\User\Model\User;

class CustomerAddressController extends Controller
{
    /**
     * @param CustomerAddressRequest $request
     * @param $idCustomer
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|Address
     */
    public function store(CustomerAddressRequest $request, $idCustomer)
    {
        try {
            $customer = User::findOrFail((int)$idCustomer);
            $addressQuery = new AddressQuery();
            $address = $addressQuery->createAddressByRequest($request, (int)$idCustomer);
            return $address;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar o endereço."], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o endereço";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Cliente não encontrada" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param CustomerAddressRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|Address
     */
    public function addAddressCustomerLogged(CustomerAddressRequest $request)
    {
        try {
            $customerAuth = Auth::user();
            $customer = User::findOrFail($customerAuth->id);
            $addressQuery = new AddressQuery();
            $address = $addressQuery->createAddressByRequest($request, $customerAuth->id);
            return $address;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar o endereço."], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o endereço";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Cliente não encontrado." : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(CustomerAddressRequest $request, $idCustomer, $idAddress)
    {
        return $this->updateAction($request, $idCustomer, $idAddress);
    }

    /**
     * @param CustomerAddressRequest $request
     * @param $idAddress
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateAction(CustomerAddressRequest $request, $idCustomer, $idAddress)
    {
        try {
            $address = Address::where('id', (int)$idAddress)
                ->where('customer_id', (int)$idCustomer)
                ->firstOrFail();;

            $data = $request->all();
            $address->update($data);
            return Address::where('id', (int)$idAddress)->get();
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Endereço não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
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
    private function delete($idCustomer, $idAddress)
    {
        try {
            $address = Address::where('customer_id', (int)$idCustomer)
                ->where('id', (int)$idAddress)
                ->delete();
            return ["errors" => false, "message" => "Excluído com sucesso."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Endereço não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

}
