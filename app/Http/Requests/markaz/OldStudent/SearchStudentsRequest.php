<?php

namespace App\Http\Requests\markaz\OldStudent;

use Illuminate\Foundation\Http\FormRequest;

class SearchStudentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'marhala' => ['nullable', 'integer'],
            'year' => ['nullable', 'string', 'regex:/^\d{4}$/'],
            'roll' => ['nullable', 'string'],
            'registration' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'year.regex' => 'বছরটি সঠিক ফরম্যাটে দিন (যেমন: ২০২৪)',
            'marhala.integer' => 'মারহালা একটি সংখ্যা হতে হবে',
        ];
    }

    /**
     * Get the search parameters
     */
    public function getSearchParams(): array
    {
        return [
            'marhala' => $this->input('marhala'),
            'year' => $this->input('year', '2024'),
            'roll' => $this->input('roll'),
            'registration' => $this->input('registration'),
            'marhalaId' => $this->header('marhalaId'),
        ];
    }
}
