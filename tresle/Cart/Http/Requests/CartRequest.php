<?php


namespace Tresle\Cart\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            "qty"            => "required|integer",
            "obs"            => "required",
            "product_id"     => "required|integer|exists:product,id",
            "additionals_id" => "required"
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
            'customer_id'    => "trim",
            'qty'            => "trim",
            'product_id'     => "trim",
            'additionals_id' => "trim",
        ];
    }

}
