<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
        return [
            //Por enquanto javascript faz o trabalho
            /*
            'empresa_nome' => 'required|min:2|max:255',
            'empresa_telefone' => 'required',
            'empresa_endereco' => 'required',
            'empresa_senha' => 'required',
            'senha_confirmacao' => 'required'
            */
            'email' => 'required|unique:companies,email',
            'nuit' => 'required|unique:companies,nuit'
        ];
    }
    public function messages(){
        
        return [
                /*
            'empresa_nome.required' => 'Obrigatorio preencher o nome da Empresa',
            'empresa_telefone.required' => 'Obrigatorio preecher o telefone da Empresa'
            */    
            'email.unique' => 'Já existe uma conta registada com esse email!',
            'nuit.unique' => 'Já existe uma conta registada com esse nuit!' 
        ];
        
    }
}
