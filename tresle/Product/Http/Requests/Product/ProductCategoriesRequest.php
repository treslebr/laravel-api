<?php

namespace Tresle\Product\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoriesRequest extends FormRequest
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
            'name'                => "min:2|max:190|"
        ];
        switch($this->method()) {
            case "POST": // CRIAÇÃO DE UM NOVO REGISTRO
                $rules["name"]    .= "|required|unique:product_categories";
                break;
            case "PUT": // ATUALIZAÇÃO DE UM REGISTRO EXISTENTE
                $rules["name"]    .= "|required|unique:product_categories,name,".$this->category;
                break;
            case "PATCH":
                $rules["name"]    .= "|filled|unique:product_categories,name,".$this->category;
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
            'name'                => "trim"
        ];
    }
}
