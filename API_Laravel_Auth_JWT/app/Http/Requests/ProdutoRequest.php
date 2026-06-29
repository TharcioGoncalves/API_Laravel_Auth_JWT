<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator):array{
        throw new HttpResponseException(response()->json([
            "status" => false,
            "erros" => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        return [
            "name" => "required|max:100",
            "description" => "required",
            "price" => "required",
            "image" => "required",
            "stock" => "required|min:5"
        ];
    }
    public function messages():array{
        return [
            "name.required" => "Campo nome é obrigatório",
            "name.max" => "Nome deve ser no máximo :max caracteres",
            "description.required" => "Campo descrição é obrigatório",
            "price.required" => "Campo preço é obrigatório",
            "image.required" => "Imagem obrigatória",
            "stock.required" => "Campo stock é obrigatório",
            "stock.min" => "Stock deve ser no minímo :min"
        ];
    }
}
