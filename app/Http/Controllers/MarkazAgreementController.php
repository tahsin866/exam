<?php

namespace App\Http\Controllers;

use App\Models\MarkazAgreement;
use App\Models\MarkazAgreementMadrasa;
use App\Models\admin\marhala_for_admin\ExamSetup;
use App\Models\madrasha;
use App\Models\Division;
use Illuminate\Support\Facades\DB;
use App\Models\District;
use App\Models\Thana;
use App\Models\schedule_setups;
use App\Models\activity_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;

class MarkazAgreementController extends Controller
{


















    public function getTableData()
    {
        $agreements = MarkazAgreement::with(['associatedMadrasas', 'activityLogs'])
            ->where('user_id', Auth::id())
            ->select('markaz_agreements.*')
            ->selectRaw('(fazilat + sanabiya_ulya + sanabiya + mutawassita + ibtedaiyyah + hifzul_quran+ qirat) as total_students')
            ->latest()
            ->get()
            ->map(function ($agreement) {
                $associatedMadrasasTotal = $agreement->associatedMadrasas->sum(function ($madrasa) {
                    return $madrasa->fazilat +
                        $madrasa->sanabiya_ulya +
                        $madrasa->sanabiya +
                        $madrasa->mutawassita +
                        $madrasa->ibtedaiyyah +
                        $madrasa->hifzul_quran +
                        $madrasa->qirat;
                });

                // Get the latest activity log status
                $latestActivityLog = $agreement->activityLogs->sortByDesc('created_at')->first();

                return [
                    'id' => $agreement->id,
                    'application_date' => $agreement->created_at->format('d/m/Y'),
                    'main_madrasa' => $agreement->madrasha_Name,
                    'exam_name' => $agreement->exam_name,
                    'associated_madrasas' => $agreement->associatedMadrasas->pluck('madrasa_Name'),
                    'main_total_students' => $agreement->total_students,
                    'associated_total_students' => $associatedMadrasasTotal,
                    'status' => $latestActivityLog ? $latestActivityLog->status : 'পেন্ডিং' // Using activity log status
                ];
            });

        return response()->json([
            'agreements' => $agreements
        ]);
    }










    // এডমিন ডাটা  ফেচ


    public function fatch()
    {
        $agreements = MarkazAgreement::with(['associatedMadrasas', 'activityLogs'])
            ->whereHas('activityLogs', function ($query) {
                $query->where('status', 'বোর্ড দাখিল');
            })
            ->latest()
            ->get()
            ->map(function ($agreement) {
                // Get the latest activity log status
                $latestActivityLog = $agreement->activityLogs->sortByDesc('created_at')->first();

                return [
                    'id' => $agreement->id,
                    'number' => $agreement->id,
                    // 'name' => $agreement->user_name,
                    'Elhaq_no' => $agreement->Elhaq_no,
                    'markaz_type' => $agreement->markaz_type,
                    'madrasha_Name' => $agreement->madrasha_Name,
                    'status' => $latestActivityLog ? $latestActivityLog->status : 'পেন্ডিং', // Use status from activity_log
                    'madrasha_code' => $agreement->madrasha_code,
                    'studentNumber' => $agreement->fazilat +
                        $agreement->sanabiya_ulya +
                        $agreement->sanabiya +
                        $agreement->mutawassita +
                        $agreement->ibtedaiyyah +
                        $agreement->hifzul_quran +
                        $agreement->associatedMadrasas->sum('fazilat') +
                        $agreement->associatedMadrasas->sum('sanabiya_ulya') +
                        $agreement->associatedMadrasas->sum('sanabiya') +
                        $agreement->associatedMadrasas->sum('mutawassita') +
                        $agreement->associatedMadrasas->sum('ibtedaiyyah') +
                        $agreement->associatedMadrasas->sum('hifzul_quran'),
                    'madrasahNumber' => $agreement->associatedMadrasas->count(),
                    'boardStatus' => 'Pending' // Placeholder status
                ];
            });

        return response()->json($agreements);
    }


    // মাদরাসার আবেদন সংশোধনী পার্ট















    // ===================================================================এডিমিন পার্ট=======================================================================










    // এডমিন আবেদন ভিউ


    public function viewDetails_for_admin($id)
    {
        $markazDetails = MarkazAgreement::with('associatedMadrasas')
            ->select(
                'id',
                'markaz_type',
'madrasha_Name',
                'created_at',
                'fazilat',
                'sanabiya_ulya',
                'sanabiya',
                'mutawassita',
                'ibtedaiyyah',
                'hifzul_quran',
                'noc_file',
                'resolution_file',
                'requirements',
                'muhtamim_consent',
                'president_consent',
                'committee_resolution',
                'user_id',
                'exam_id',
                'exam_name'
            )
            ->findOrFail($id);
        $markazDetails->created_at = $markazDetails->created_at->timestamp * 1000;
        // $markazDetails->created_at = $markazDetails->created_at->format('d/m/Y');
        $markazDetails->president_consent = $markazDetails->president_consent ? Storage::url($markazDetails->president_consent) : null;
        $markazDetails->resolution_file = $markazDetails->resolution_file ? Storage::url($markazDetails->resolution_file) : null;

        return inertia('markaz_for_admin/markaz_apply_details_view', [
            'markazDetails' => $markazDetails
        ]);
    }


    // এডমিন আবেদন অনুমোদন

    public function approveApplication(Request $request, $id)
    {
        // Find the agreement
        $agreement = MarkazAgreement::find($id);

        if (!$agreement) {
            return back()->withErrors(['error' => 'আবেদন পাওয়া যায়নি!']);
        }

        $adminId = Auth::guard('admin')->id();
        $adminName = Auth::guard('admin')->user()->name;
        $admin_position = Auth::guard('admin')->user()->designation;

        // Create activity log entry
        $logData = [
            'admin_id' => $adminId,
            'admin_name' => $adminName,
            'admin_position' => $admin_position,
            'markaz_agreement_id' => $agreement->id,
            'status' => 'অনুমোদন',
            'processed_at' => now(),
        ];

        // Insert into activity_logs
        $inserted = activity_log::create($logData);

        if ($inserted) {
            return back()->with('success', 'আবেদন সফলভাবে অনুমোদন করা হয়েছে!');
        }

        return back()->withErrors(['error' => 'আপডেট করতে সমস্যা হয়েছে!']);
    }

    // এডমিন আবেদন ফেরত



    public function return(Request $request, $id)
    {
        // Find the agreement by ID
        $agreement = MarkazAgreement::find($id);

        // If the agreement is not found, return error
        if (!$agreement) {
            return back()->withErrors(['error' => 'আবেদন পাওয়া যায়নি!']);
        }

        $adminId = Auth::guard('admin')->id();
        $adminName = Auth::guard('admin')->user()->name;
        $adminName = Auth::guard('admin')->user()->name;
        $admin_position = Auth::guard('admin')->user()->designation;

        // Initialize feedback data
        $feedbackData = [
            'admin_id' => $adminId,
            'admin_name' => $adminName,
            'admin_position' => $admin_position,
            'markaz_agreement_id' => $agreement->id,
            'status' => 'বোর্ড ফেরত', // Update status
            'admin_message' => $request->message, // Insert the admin's message
            'processed_at' => now(),
        ];

        // Check if an image is uploaded and store it
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/feedback', 'public');
            $feedbackData['admin_feedback_image'] = $imagePath; // Store the image path
        }

        // Insert the feedback into the activity_logs table
        $inserted = activity_log::create($feedbackData);

        // If the insert was successful, return success message
        if ($inserted) {
            return back()->with('success', 'আবেদন সফলভাবে ফেরত পাঠানো হয়েছে!');
        } else {
            return back()->withErrors(['error' => 'আপডেট করতে সমস্যা হয়েছে!']);
        }
    }
}
