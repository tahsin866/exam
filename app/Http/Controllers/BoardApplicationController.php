<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\negMumtahinAplication;
use App\Models\negran_mumtahin_log;

class BoardApplicationController extends Controller
{
public function getNegranData(Request $request)
{
    // Get applications that have "বোর্ড দাখিল" status in negran_mumtahin_logs table
    $applications = negMumtahinAplication::select(
        'neg_mumtahin_aplications.id',
        'neg_mumtahin_aplications.name',
        'neg_mumtahin_aplications.mobile',
        'neg_mumtahin_aplications.birth_date',
        'neg_mumtahin_aplications.gender',
        'neg_mumtahin_aplications.application_type',
        'neg_mumtahin_aplications.teaching_experience',
        'neg_mumtahin_aplications.madrasha_id',
        'negran_mumtahin_logs.status'
    )
    ->join('negran_mumtahin_logs', 'neg_mumtahin_aplications.id', '=', 'negran_mumtahin_logs.application_id')
    ->where('negran_mumtahin_logs.status', '=', 'বোর্ড দাখিল')
    ->get();

    // Get madrasha names separately and add to applications
    foreach ($applications as $application) {
        // Make sure we're using the correct madrasha_id
        if ($application->madrasha_id) {
            // Use DB facade to avoid Eloquent's potential collation issues
            $madrasha = DB::table('madrasha')
                ->where('id', '=', $application->madrasha_id)
                ->first();

            // Add madrasha name to the application object
            if ($madrasha) {
                $application->madrasha_name = $madrasha->MNName;
            } else {
                $application->madrasha_name = 'মাদরাসা পাওয়া যায়নি';
            }
        } else {
            $application->madrasha_name = 'মাদরাসা আইডি নেই';
        }
    }

    // Get unique application types for filter
    $applicationTypes = negMumtahinAplication::distinct()
        ->pluck('application_type')
        ->toArray();

    // Return JSON response
    return response()->json([
        'applications' => $applications,
        'applicationTypes' => $applicationTypes
    ]);
}



}
