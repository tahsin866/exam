<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use inertia\inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\madrasha;
use App\Models\markaz_change;
use App\Models\admin\marhala_for_admin\ExamSetup;
class markazChangeController extends Controller
{
    //


    public function showUserCenters()
    {
        $userId = Auth::id();

        // Get madrasha_id from users table
        $user = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$user || !isset($user->madrasha_id)) {
            return response()->json(['centers' => [], 'error' => 'User or madrasha_id not found']);
        }

        $madrashaId = $user->madrasha_id;

        // Find record in stu_rledger_p where MRID = madrasha_id
        $studentRecord = DB::table('stu_rledger_p')
            ->where('MRID', $madrashaId)
            ->first();

        if (!$studentRecord || !isset($studentRecord->MDID)) {
            return response()->json(['centers' => [], 'error' => 'Student record or MDID not found']);
        }

        $mdid = $studentRecord->MDID;

        // Get madrasha details using MDID
        $madrashaRecord = DB::table('madrasha')
            ->where('id', $mdid)
            ->first();

        if (!$madrashaRecord) {
            return response()->json(['centers' => [], 'error' => 'Madrasha record not found']);
        }

        $centers = [];

        // Check for Darsiyat center
        if ($madrashaRecord->CenterD == 1) {
            $centers['darsiyat'] = 'দারসিয়াত মারকায: ' . $madrashaRecord->MNName;
        }

        // Check for Hifz center
        if ($madrashaRecord->CenterH == 1) {
            $centers['hifz'] = 'হিফজ মারকায: ' . $madrashaRecord->MNName;
        }

        // Check for Kirat center
        if ($madrashaRecord->CenterK == 1) {
            $centers['kirat'] = 'কিরাত মারকায: ' . $madrashaRecord->MNName;
        }

        return response()->json(['centers' => $centers]);
    }









    public function showMarkaZCenters()
    {
        $userId = Auth::id();

        // Get madrasha_id from users table
        $user = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$user || !isset($user->madrasha_id)) {
            return response()->json(['centers' => [], 'error' => 'User or madrasha_id not found']);
        }

        $madrashaId = $user->madrasha_id;

        // Find record in stu_rledger_p where MRID = madrasha_id
        $studentRecord = DB::table('stu_rledger_p')
            ->where('MRID', $madrashaId)
            ->first();

        if (!$studentRecord || !isset($studentRecord->MDID)) {
            return response()->json(['centers' => [], 'error' => 'Student record or MDID not found']);
        }

        $mdid = $studentRecord->MDID;

        // Get madrasha details using MDID
        $madrashaRecord = DB::table('madrasha')
            ->where('id', $mdid)
            ->first();

        if (!$madrashaRecord) {
            return response()->json(['centers' => [], 'error' => 'Madrasha record not found']);
        }

        $centers = [];

        // Check for Darsiyat center
        if ($madrashaRecord->CenterD == 1) {
            $centers['darsiyat'] = ' ' . $madrashaRecord->MNName;
        }

        // Check for Hifz center
        if ($madrashaRecord->CenterH == 1) {
            $centers['hifz'] = 'হিফজ মারকায: ' . $madrashaRecord->MNName;
        }

        // Check for Kirat center
        if ($madrashaRecord->CenterK == 1) {
            $centers['kirat'] = 'কিরাত মারকায: ' . $madrashaRecord->MNName;
        }

        return response()->json(['centers' => $centers]);
    }









    public function getMadrashasChange()
    {
        // লগইন করা ইউজারের ডেটা নিয়ে আসি
        $user = Auth::user();

        // markaz_serial আছে এমন কন্ডিশন (খালি নয়, 0 নয়, null নয়)
        $markazNotEmptyCondition = function($query) {
            $query->whereNotNull('markaz_serial')
                  ->where('markaz_serial', '!=', 0)
                  ->where('markaz_serial', '!=', '');
        };

        // ইউজারের MType চেক করি
        if ($user->MType == 1) {
            // MType=1 হলে শুধু markaz_serial আছে এবং MType=1 এর মাদরাসাগুলো নিয়ে আসি
            $madrashas = madrasha::select('id', 'MName as name', 'ElhaqNo')
                ->where('MType', 1)
                ->where($markazNotEmptyCondition)
                ->orderBy('MName')
                ->get();
        } else if ($user->MType == 0) {
            // ইউজারের MType=0 হলে MType=0 এর মাদরাসাগুলো নিয়ে আসি
            // এবং markaz_serial আছে
            $madrashas = madrasha::select('id', 'MName as name', 'ElhaqNo')
                ->where('MType', 0)
                ->where($markazNotEmptyCondition)
                ->orderBy('MName')
                ->get();
        } else {
            // অন্যান্য ইউজারদের জন্য markaz_serial আছে এমন মাদরাসা দেখাবে
            $madrashas = madrasha::select('id', 'MName as name', 'ElhaqNo')
                ->where($markazNotEmptyCondition)
                ->orderBy('MName')
                ->get();
        }

        return response()->json($madrashas);
    }










  public function MarkazStore(Request $request)
{
    $request->validate([
        'markaz_type' => 'required|in:darsiyat,hifz,kirat',
        'asking_madrasha' => 'required|string',
        'onapotti_potro' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'shommoti_potro' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Get current user
    $user = Auth::user();

    // Get latest exam setup
    $examSetup = ExamSetup::latest()->first();
    if (!$examSetup) {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'No exam setup found'], 404);
        }
        return back()->with('error', 'No exam setup found');
    }

    // Upload files
    $onapottiPath = $request->file('onapotti_potro')->store('markaz_changes/onapotti', 'public');
    $shommotiPath = $request->file('shommoti_potro')->store('markaz_changes/shommoti', 'public');

    // Get the madrasha ID from the asking_madrasha string
    // Assuming the format is "MName (ElhaqNo)" or just the ID is passed
    $askingMadrashaId = $request->input('markaz_id');

    // Create markaz change record
    $markazChange = new markaz_change();
    $markazChange->user_id = $user->id;
    $markazChange->central_exam_id = $examSetup->id;
    $markazChange->madrasha_id = $user->madrasha_id;
    $markazChange->madrasha_name = $user->madrasha_name; // Get name directly from user
    $markazChange->madrasha_code = $user->custom_code; // Get custom_code directly from user
    $markazChange->asking_madrasha = $request->asking_madrasha;
    $markazChange->markaz_id = $askingMadrashaId; // Store the madrasha ID here
    $markazChange->markaz_type = $request->markaz_type;
    $markazChange->onapotti_potro = $onapottiPath;
    $markazChange->shommoti_potro = $shommotiPath;
    $markazChange->save();

    // Check if this is an Inertia request or an API request
    if ($request->wantsJson()) {
        return response()->json([
            'message' => 'আপনার মারকাজ পরিবর্তনের আবেদন সফলভাবে জমা হয়েছে',
            'markaz_change' => $markazChange
        ], 201);
    }

    // For Inertia requests
    return back()->with('success', 'আপনার মারকাজ পরিবর্তনের আবেদন সফলভাবে জমা হয়েছে');
}








//================ এডমিন পেনেলে============


    public function getMarkazChange(Request $request)
    {
        $markazChanges = DB::table('markaz_changes')
            ->select('madrasha_name', 'asking_madrasha', 'madrasha_code','created_at', 'markaz_type', 'id')
            ->get();

        // markaz_type ফিল্ডের ভ্যালু পরিবর্তন করে বাংলায় দেখানো
        $markazChanges = $markazChanges->map(function($item) {
            // markaz_type এর ভ্যালু অনুযায়ী বাংলা টেক্সট সেট করা
            switch(strtolower($item->markaz_type)) {
                case 'kirat':
                    $item->markaz_type = 'কিরাআত';
                    break;
                case 'darsiyat':
                    $item->markaz_type = 'দারসিয়াত';
                    break;
                case 'hifz':
                    $item->markaz_type = 'হিফজুল কোরাআন';
                    break;
                // অন্যান্য টাইপের জন্য আরও কেস যোগ করতে পারেন
            }

            return $item;
        });

        return response()->json($markazChanges);
    }












public function approveMarkazChange($id){
    try {
        // Find the markaz change request
        $markazChange = markaz_change::findOrFail($id);

        // Get the madrasha ID and the new markaz ID
        $madrashaId = $markazChange->madrasha_id;
        $newMarkazId = $markazChange->markaz_id;

        // Update the stu_rledger_p table
        // Find all records where MRID matches the madrasha ID
        $updatedLedgerCount = DB::table('stu_rledger_p')
            ->where('MRID', $madrashaId)
            ->update(['MDID' => $newMarkazId]);

        // Update the reg_stu_informations table
        // Find all records where madrasha_id matches and update markaz_id
        $updatedStudentCount = DB::table('reg_stu_informations')
            ->where('madrasha_id', $madrashaId)
            ->update(['markaz_id' => $newMarkazId]);

        // Update the markaz_change status to approved
        $markazChange->status = 'approved';
        $markazChange->approved_at = now();
        $markazChange->approved_by = Auth::id();
        $markazChange->save();

        return response()->json([
            'success' => true,
            'message' => 'মারকাজ পরিবর্তন সফলভাবে অনুমোদিত হয়েছে',
            'updated_ledger_records' => $updatedLedgerCount,
            'updated_student_records' => $updatedStudentCount
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'মারকাজ পরিবর্তন অনুমোদন করতে সমস্যা হয়েছে',
            'error' => $e->getMessage()
        ], 500);
    }
}




}
