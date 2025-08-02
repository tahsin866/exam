<?php

namespace App\Http\Requests\MarkazAgreement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarkazAgreementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'markaz_type' => 'required|string',
            'fazilat' => 'nullable|integer|min:0',
            'sanabiya_ulya' => 'nullable|integer|min:0',
            'sanabiya' => 'nullable|integer|min:0',
            'mutawassita' => 'nullable|integer|min:0',
            'ibtedaiyyah' => 'nullable|integer|min:0',
            'hifzul_quran' => 'nullable|integer|min:0',
            'qirat' => 'nullable|integer|min:0',
            'requirements' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'markaz_type.required' => 'মারকায টাইপ নির্বাচন করুন',
            'fazilat.integer' => 'ফযীলত সংখ্যা হতে হবে',
            'fazilat.min' => 'ফযীলত ০ বা তার বেশি হতে হবে',
            'sanabiya_ulya.integer' => 'সানাবিয়া উলইয়া সংখ্যা হতে হবে',
            'sanabiya.integer' => 'সানাবিয়া সংখ্যা হতে হবে',
            'mutawassita.integer' => 'মুতাওয়াসসিতা সংখ্যা হতে হবে',
            'ibtedaiyyah.integer' => 'ইবতেদাইয়্যাহ সংখ্যা হতে হবে',
            'hifzul_quran.integer' => 'হিফজুল কোরআন সংখ্যা হতে হবে',
            'qirat.integer' => 'ইলমুল কিরাআত সংখ্যা হতে হবে'
        ];
    }
}
