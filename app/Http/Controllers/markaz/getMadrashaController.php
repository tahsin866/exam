<?php

namespace App\Http\Controllers\markaz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\madrasha;

use Illuminate\Support\Facades\Auth;


class getMadrashaController extends Controller
{



public function getMadrashas()
    {
        // লগইন করা ইউজারের ডেটা নিয়ে আসি
        $user = Auth::user();

        // markaz_serial খালি বা 0 বা খালি স্ট্রিং এর কন্ডিশন
        $markazEmptyCondition = function($query) {
            $query->whereNull('markaz_serial')
                  ->orWhere('markaz_serial', 0)
                  ->orWhere('markaz_serial', '');
        };

        // ইউজারের MType চেক করি
        if ($user->MType == 1) {
            // MType=1 হলে শুধু markaz_serial খালি বা 0 এবং MType=1 এর মাদরাসাগুলো নিয়ে আসি
            $madrashas = madrasha::select('id', 'MName as name', 'ElhaqNo')
                ->where('MType', 1)
                ->where($markazEmptyCondition)
                ->orderBy('MName')
                ->get();
        } else if ($user->MType == 0) {
            // ইউজারের MType=0 হলে MType=0 এর মাদরাসাগুলো নিয়ে আসি
            // এবং markaz_serial খালি বা 0 বা খালি স্ট্রিং হতে হবে
            $madrashas = madrasha::select('id', 'MName as name', 'ElhaqNo')
                ->where('MType', 0)
                ->where($markazEmptyCondition)
                ->orderBy('MName')
                ->get();
        } else {
            // অন্যান্য ইউজারদের জন্য সব মাদরাসা দেখাবে
            $madrashas = madrasha::select('id', 'MName as name', 'ElhaqNo')
                ->orderBy('MName')
                ->get();
        }

        return response()->json($madrashas);
    }



}
