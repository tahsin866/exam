<?php

namespace App\Http\Requests\madrasha\StudentRegistration\OldStudent;

use Illuminate\Foundation\Http\FormRequest;

class SearchStudentsRequest extends FormRequest
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
        return [
            'marhala' => 'nullable|integer',
            'year' => 'nullable|string|max:4',
            'roll' => 'nullable|string|max:50',
            'registration' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'marhala.integer' => 'মারহালা একটি সংখ্যা হতে হবে',
            'year.max' => 'বছর সর্বোচ্চ ৪ সংখ্যার হতে পারে',
            'roll.max' => 'রোল নম্বর সর্বোচ্চ ৫০ ক্যারেক্টারের হতে পারে',
            'registration.max' => 'রেজিস্ট্রেশন নম্বর সর্বোচ্চ ৫০ ক্যারেক্টারের হতে পারে',
        ];
    }

    /**
     * Get the default year if not provided
     */
    public function getYear(): string
    {
        return $this->filled('year') ? $this->year : '2024';
    }

    /**
     * Get marhala ID from header
     */
    public function getMarhalaId(): ?string
    {
        return $this->header('marhalaId');
    }
}
