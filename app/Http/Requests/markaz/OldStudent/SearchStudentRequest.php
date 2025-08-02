<?php

namespace App\Http\Requests\markaz\OldStudent;

use Illuminate\Foundation\Http\FormRequest;

class SearchStudentRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'marhala' => 'sometimes|integer|min:1',
            'year' => 'sometimes|string|max:4',
            'roll' => 'sometimes|string|max:50',
            'registration' => 'sometimes|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'marhala.integer' => 'মারহালা একটি সংখ্যা হতে হবে',
            'marhala.min' => 'মারহালা ১ বা তার বেশি হতে হবে',
            'year.string' => 'বছর একটি স্ট্রিং হতে হবে',
            'year.max' => 'বছর সর্বোচ্চ ৪ অক্ষরের হতে পারে',
            'roll.string' => 'রোল একটি স্ট্রিং হতে হবে',
            'roll.max' => 'রোল সর্বোচ্চ ৫০ অক্ষরের হতে পারে',
            'registration.string' => 'রেজিস্ট্রেশন একটি স্ট্রিং হতে হবে',
            'registration.max' => 'রেজিস্ট্রেশন সর্বোচ্চ ৫০ অক্ষরের হতে পারে',
        ];
    }

    /**
     * Get the year value, defaulting to 2024 if not provided
     */
    public function getYear(): string
    {
        return $this->input('year', '2024');
    }
}
