<?php

namespace Tresle\Product\Http\Requests\Additional;

use Illuminate\Foundation\Http\FormRequest;

class AdditionalRequest extends FormRequest
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
            'name'                           => "min:2|max:190",
            "status"                         => "boolean",
            "price"                          => "numeric",
            "product_additional_category_id" =>  "exists:product_additional_category,id"
        ];
        switch($this->method()) {
            case "POST": // CRIAÇÃO DE UM NOVO REGISTRO
                $rules["name"]    .= "|required|unique:product_additional";
                $rules["product_additional_category_id"]  .= "|required";
                break;
            case "PUT": // ATUALIZAÇÃO DE UM REGISTRO EXISTENTE
                $rules["name"]    .= "|required|unique:product_additional,name,".$this->additional;
                $rules["product_additional_category_id"]  .= "|required";
                break;
            case "PATCH": // ATUALIZAÇÃO DE UM REGISTRO EXISTENTE
                $rules["name"]    .= "|filled|unique:product_additional,name,".$this->additional;
                $rules["product_additional_category_id"]  .= "|filled";
                break;
            default:break;
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
            'name'                           => "trim",
            "status"                         => "trim",
            "price"                          => "trim",
            "product_additional_category_id" => "trim:product_additional_category,id"
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_additional_category_id.exists' => 'Essa categoria não existe'
        ];
    }
}
