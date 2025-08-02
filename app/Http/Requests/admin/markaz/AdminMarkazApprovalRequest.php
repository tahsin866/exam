<?php

namespace App\Http\Requests\admin\markaz;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminMarkazApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->user_type === 'admin';
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:markaz_agreements,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'আবেদন আইডি প্রয়োজন।',
            'id.integer' => 'আবেদন আইডি সংখ্যা হতে হবে।',
            'id.exists' => 'আবেদনটি খুঁজে পাওয়া যায়নি।',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
