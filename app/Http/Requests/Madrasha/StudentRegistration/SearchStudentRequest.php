<?php

namespace App\Http\Requests\Madrasha\StudentRegistration;

use Illuminate\Foundation\Http\FormRequest;

class SearchStudentRequest extends FormRequest
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
            'marhala' => 'sometimes|integer',
            'year' => 'sometimes|string',
            'roll' => 'sometimes|string',
            'registration' => 'sometimes|string',
        ];
    }

    /**
     * Add default values to request
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'year' => $this->year ?? '2024',
        ]);
    }
}
