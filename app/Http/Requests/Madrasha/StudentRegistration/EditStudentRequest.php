<?php

namespace App\Http\Requests\Madrasha\StudentRegistration;

use Illuminate\Foundation\Http\FormRequest;

class EditStudentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'data' => 'sometimes|string',
            'roll' => 'sometimes|required_without:data|string',
            'reg_id' => 'sometimes|required_without:data|string',
            'CID' => 'sometimes|required_without:data|string',
        ];
    }
}
