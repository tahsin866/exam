<?php

namespace App\Http\Controllers\Auth;
use App\Models\madrasha;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;



class madrasha_check_for_userController extends Controller
{
    // public function check(Request $request)
    // {


    //     $request->validate([
    //         'ElhaqNo' => 'required|string',
    //         'Mobile' => 'required|string',
    //     ]);

    //     $madrasha = madrasha::where('ElhaqNo', $request->ElhaqNo)
    //                         ->where('Mobile', $request->Mobile)
    //                         ->first();

    //                         if ($madrasha) {
    //                             $customCode = 'AW' . rand(10000, 99999);

    //                             // Use Inertia::render instead of redirect
    //                             return Inertia::render('Auth/Register', [
    //                                 'success' => 'আপনার তথ্য সঠিক, রেজিস্ট্রেশন পেজে নিয়ে যাওয়া হচ্ছে।',
    //                                 'madrasha_id' => $madrasha->id,
    //                                 'madrasha_name' => $madrasha->MName_uni,
    //                                 'thana' => $madrasha->Thana_uni,
    //                                 'post' => $madrasha->Post_uni,
    //                                 'custom_code' => $customCode,
    //                             ]);
    //                         } else {
    //         return back()->withErrors([
    //             'ElhaqNo' => 'আপনার ইলহাক নম্বর বা মোবাইল নম্বর সঠিক নয়।'
    //         ]);
    //     }
    // }










    public function check(Request $request)
    {
        $request->validate([
            'ElhaqNo' => 'required|string',
            'Mobile' => 'required|string',
        ]);

        $madrasha = madrasha::where('ElhaqNo', $request->ElhaqNo)
                            ->where('Mobile', $request->Mobile)
                            ->first();

        if ($madrasha) {
            $customCode = 'AW' . rand(10000, 99999);

            // Store in session
            session([
                'madrasha_id' => $madrasha->id,
                'madrasha_name' => $madrasha->MName,
                'thana' => $madrasha->Vill,
                'post' => $madrasha->Post,
                'Stage' => $madrasha->Stage,
                'MType' => $madrasha->MType,
                'markaz_serial' => $madrasha->markaz_serial,
                'custom_code' => $customCode,
                'madrasha_verified_at' => now()
            ]);

            return redirect()->route('register');
        }

        return back()->withErrors([
            'ElhaqNo' => 'আপনার ইলহাক নম্বর বা মোবাইল নম্বর সঠিক নয়।'
        ]);
    }




}
