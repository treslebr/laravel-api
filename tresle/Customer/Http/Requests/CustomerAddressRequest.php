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
            'postcode'    => "required|min:2|max:190",
            'country'     => "required|min:2|max:190",
            'state'       => "required|min:2|max:190",
            'city'        => "required|min:2|max:190",
            'region'      => "required|min:2|max:190",
            'street_1'    => "required|min:2|max:190",
            'street_2'    => "required|min:2|max:190",
            'street_3'    => "required|min:2|max:190",
            'street_4'    => "min:2|max:190"
        ];
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
            'street_4'    => "trim"
        ];
    }

}
