<?php

namespace App\Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaDetalleRequest extends FormRequest
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
        switch ($this->method()) {

            case 'PATCH':
                $rules = $this->rules_editar();
                break;
            default:
                $rules = $this->rules_guardar();
                break;
        }
        return $rules;
    }
    public function rules_guardar(){
        return [
            'usuario_id' => 'required',
            'sala_id' => 'required',
            'admin' => 'required',

        ];
    }

    public function rules_editar(){
        return [
            'usuario_id' => 'required',
            'sala_id' => 'required',
            'admin' => 'required',
        ];
    }
}
