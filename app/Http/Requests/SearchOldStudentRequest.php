<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SearchOldStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'marhala' => 'nullable|integer',
            'year' => 'nullable|string',
            'roll' => 'nullable|string',
            'registration' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'marhala.integer' => 'মারহালা অবশ্যই একটি সংখ্যা হতে হবে',
            'year.string' => 'বছর অবশ্যই একটি স্ট্রিং হতে হবে',
            'roll.string' => 'রোল অবশ্যই একটি স্ট্রিং হতে হবে',
            'registration.string' => 'রেজিস্ট্রেশন অবশ্যই একটি স্ট্রিং হতে হবে',
        ];
    }
}
