<?php


namespace Tresle\Shipping\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Shipping extends FormRequest
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
            'location'    => "min:2|max:190|",
            'price'    => "numeric",
        ];

        switch($this->method()) {
            case "POST": // CRIAÇÃO DE UM NOVO REGISTRO
                $rules["location"]    .= "|required|unique:shipping";
                break;
            case "PATCH": // ATUALIZAÇÃO DE UMA PARTE DO REGISTRO EXISTENTE
                $rules['location']  .= "filled|unique:shipping,location,".$this->id;
                break;
            case "PUT": // ATUALIZAÇÃO DE UM REGISTRO EXISTENTE
                $rules["location"]    .= "|required|unique:shipping,location,".$this->id;
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
            'location'    => "trim|lowercase"
        ];
    }

}

