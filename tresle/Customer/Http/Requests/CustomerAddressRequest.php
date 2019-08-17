<?php


namespace Tresle\Customer\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressRequest  extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'postcode'    => "min:2|max:190|",
            'country'     => "min:2|max:190|",
            'state'       => "min:2|max:190|",
            'city'        => "min:2|max:190|",
            'region'      => "min:2|max:190|",
            'street_1'    => "min:2|max:190|",
            'street_2'    => "min:2|max:190|",
            'street_3'    => "min:2|max:190|",
            'shipping_id' => "integer|exists:shipping,id|"
        ];

        switch($this->method()) {
            case "PATCH": // ATUALIZAÇÃO DE UMA PARTE DO REGISTRO EXISTENTE
                $rules['postcode']  .= "filled";
                $rules['country']   .= "filled";
                $rules['state']     .= "filled";
                $rules['city']      .= "filled";
                $rules['region']    .= "filled";
                $rules['street_1']  .= "filled";
                $rules['street_2']  .= "filled";
                $rules['shipping_id']  .= "filled";
                break;
            default :
                $rules['postcode']  .= "required";
                $rules['country']   .= "required";
                $rules['state']     .= "required";
                $rules['city']      .= "required";
                $rules['region']    .= "required";
                $rules['street_1']  .= "required";
                $rules['street_2']  .= "required";
                $rules['shipping_id']  .= "required";
                break;
        }
        return $rules;
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'postcode'    => "trim",
            'country'     => "trim",
            'state'       => "trim",
            'city'        => "trim",
            'region'      => "trim",
            'street_1'    => "trim",
            'street_2'    => "trim",
            'street_3'    => "trim",
            'shipping_id' => "trim"
        ];
    }

}
