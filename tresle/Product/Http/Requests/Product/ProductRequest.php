<?php

namespace Tresle\Product\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name'                => "min:2|max:190|",
            "product_category_id" => "integer",
            "status"              => "boolean",
            "price"               => "numeric"
        ];
        switch($this->method()) {
            case "POST": // CRIAÇÃO DE UM NOVO REGISTRO
                $rules["name"]    .= "|required|unique:product";
                $rules["product_category_id"]  .= "|required";
                break;
            case "PUT": // ATUALIZAÇÃO DE UM REGISTRO EXISTENTE
                $rules["name"]    .= "|required|unique:product,name,".$this->product;
                $rules["product_category_id"]  .= "|required";
                break;
            case "PATCH": // ATUALIZAÇÃO DE UM REGISTRO EXISTENTE
                $rules["name"]    .= "|filled|unique:product,name,".$this->product;
                $rules["product_category_id"]  .= "|filled";
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
            'name'                => "trim",
            'product_category_id' => "trim",
            'status'              => "trim",
            'price'               => "trim"
        ];
    }
}
