<?php

namespace App\Http\Requests\admin\markaz;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminMarkazReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->user_type === 'admin';
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:markaz_agreements,id',
            'message' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'আবেদন আইডি প্রয়োজন।',
            'id.integer' => 'আবেদন আইডি সংখ্যা হতে হবে।',
            'id.exists' => 'আবেদনটি খুঁজে পাওয়া যায়নি।',
            'message.required' => 'ফেরতের কারণ লিখুন।',
            'message.string' => 'ফেরতের কারণ টেক্সট হতে হবে।',
            'message.max' => 'ফেরতের কারণ সর্বোচ্চ ১০০০ অক্ষর হতে পারে।',
            'image.image' => 'ফাইলটি একটি ছবি হতে হবে।',
            'image.mimes' => 'ছবি jpeg, png, jpg অথবা gif ফরম্যাটে হতে হবে।',
            'image.max' => 'ছবির সাইজ সর্বোচ্চ ২ মেগাবাইট হতে পারে।',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
