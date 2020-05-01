<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContact extends FormRequest
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
            'image'=>'nullable|sometimes|image|mimes:jpeg,jpg,png',
            'name'=>'required',
            'email'=>["required","email",
                Rule::unique('contacts', 'email')->ignore($this->contact)
            ]
        ];
    }
}
