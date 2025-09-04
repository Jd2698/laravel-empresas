<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\StatusEnum;

class EmpresaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nombre' => ['string', 'max:40'],
            'nit' => ['numeric', 'min_digits:8'],
            'direccion' => ['string', 'min:10'],
            'telefono' => ['numeric', 'digits:10'],
        ];

        if ($this->isMethod('post')) {
            $rules['nombre'][] = 'required';
            $rules['nit'][] = 'required';
            $rules['nit'][] = 'unique:empresas,nit';
            $rules['direccion'][] = 'required';
            $rules['telefono'][] = 'required';
        }


        if ($this->isMethod('patch')) {
            $rules['estado'] = ['nullable', Rule::in(StatusEnum::cases())];
            $rules['nombre'][] = 'nullable';
            $rules['direccion'][] = 'nullable';
            $rules['telefono'][] = 'nullable';

            unset($rules['nit']);
        }

        return $rules;
    }
}
