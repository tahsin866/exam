<?php

namespace App\Http\Controllers\markaz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use App\Services\MarkazAgreement\StoreMarkazAgreementService;
use App\Services\MarkazAgreement\UpdateMarkazAgreementService;
use App\Services\MarkazAgreement\MarkazStatusService;
use App\Models\MarkazAgreementLog;
use Illuminate\Contracts\Cache\Store;

class markazApplicationController extends Controller
{

     public function index()
    {
        $agreements = MarkazAgreement::with('associatedMadrasas')->latest()->paginate(10);

        return inertia('Markaz/markaz_agreement_form', [
            'agreements' => $agreements
        ]);
    }





    public function getLatestExam()
    {
        return ExamSetup::latest()->first();

    }




    // মাদরাসার মারকায আবেদন

// public function store(Request $request)
// {
//     // Get user with madrasha relationship
//     $user = Auth::user();
//     $madrashaData = madrasha::where('id', $user->madrasha_id)->first();

//     // Get exam setup data
//     $examSetup = ExamSetup::select('id', 'exam_name')->latest()->first();

//     // Create main agreement
//     $markazAgreement = new MarkazAgreement();

//     // User and Exam related data
//     $markazAgreement->user_id = $user->id;
//     $markazAgreement->madrasha_Name = $user->madrasha_name;
//     $markazAgreement->madrasha_code = $user->custom_code;
//     $markazAgreement->exam_id = $examSetup->id;
//     $markazAgreement->exam_name = $examSetup->exam_name;

//     // Madrasha related data - using relationships to get the actual names
//     if ($madrashaData) {
//         $division = Division::where('ID', $madrashaData->DID)->first();
//         $markazAgreement->division = $division ? $division->Division : '';
//         $markazAgreement->division_id = $madrashaData->DID ?? 0;

//         $district = District::where('DesID', $madrashaData->DISID)->first();
//         $markazAgreement->district = $district ? $district->District : '';
//         $markazAgreement->district_id = $madrashaData->DISID ?? 0;

//         $thana = Thana::where('Thana_ID', $madrashaData->TID)->first();
//         $markazAgreement->thana_uni = $thana ? $thana->Thana : '';
//         $markazAgreement->tid = $madrashaData->TID ?? 0;

//         $markazAgreement->mtype = $madrashaData->MType ?? 0;
//         $markazAgreement->Stage = $madrashaData->Stage ?? 0;
//         $markazAgreement->Elhaq_no = $madrashaData->ElhaqNo ?? '';
//     } else {
//         $markazAgreement->division = '';
//         $markazAgreement->division_id = 0;
//         $markazAgreement->district = '';
//         $markazAgreement->district_id = 0;
//         $markazAgreement->thana_uni = '';
//         $markazAgreement->tid = 0;
//         $markazAgreement->mtype = 0;
//         $markazAgreement->Stage = 0;
//         $markazAgreement->Elhaq_no = '';
//     }

//     // Student counts
//     $markazAgreement->markaz_type = $request->markaz_type;
//     $markazAgreement->fazilat = $request->fazilat;
//     $markazAgreement->sanabiya_ulya = $request->sanabiya_ulya;
//     $markazAgreement->sanabiya = $request->sanabiya;
//     $markazAgreement->mutawassita = $request->mutawassita;
//     $markazAgreement->ibtedaiyyah = $request->ibtedaiyyah;
//     $markazAgreement->hifzul_quran = $request->hifzul_quran;
//     $markazAgreement->qirat = $request->qirat;

//     // File handling
//     if ($request->hasFile('noc_file') && $request->file('noc_file') instanceof \Illuminate\Http\UploadedFile) {
//         $markazAgreement->noc_file = $request->file('noc_file')->store('markaz/noc');
//     }

//     if ($request->hasFile('resolution_file') && $request->file('resolution_file') instanceof \Illuminate\Http\UploadedFile) {
//         $markazAgreement->resolution_file = $request->file('resolution_file')->store('markaz/resolution');
//     }

//     // Requirements
//     $markazAgreement->requirements = $request->requirements;

//     // Consent files
//     if ($request->hasFile('muhtamim_consent') && $request->file('muhtamim_consent') instanceof \Illuminate\Http\UploadedFile) {
//         $markazAgreement->muhtamim_consent = $request->file('muhtamim_consent')->store('markaz/consent');
//     }

//     if ($request->hasFile('president_consent') && $request->file('president_consent') instanceof \Illuminate\Http\UploadedFile) {
//         $markazAgreement->president_consent = $request->file('president_consent')->store('markaz/consent');
//     }

//     if ($request->hasFile('committee_resolution') && $request->file('committee_resolution') instanceof \Illuminate\Http\UploadedFile) {
//         $markazAgreement->committee_resolution = $request->file('committee_resolution')->store('markaz/consent');
//     }

//     $markazAgreement->save();

//     // Handle associated madrasas
//     if ($request->has('associated_madrasas') && is_array($request->associated_madrasas)) {
//         foreach ($request->associated_madrasas as $madrasaData) {
//             $associatedMadrasa = new MarkazAgreementMadrasa();
//             $associatedMadrasa->markaz_agreement_id = $markazAgreement->id;
//             $associatedMadrasa->madrasa_Name = $madrasaData['madrasa_Name'] ?? '';
//             $associatedMadrasa->madrasa_id = $madrasaData['madrasa_id'] ?? null;

//             $associatedMadrasa->fazilat = $madrasaData['fazilat'] ?? 0;
//             $associatedMadrasa->sanabiya_ulya = $madrasaData['sanabiya_ulya'] ?? 0;
//             $associatedMadrasa->sanabiya = $madrasaData['sanabiya'] ?? 0;
//             $associatedMadrasa->mutawassita = $madrasaData['mutawassita'] ?? 0;
//             $associatedMadrasa->ibtedaiyyah = $madrasaData['ibtedaiyyah'] ?? 0;
//             $associatedMadrasa->hifzul_quran = $madrasaData['hifzul_quran'] ?? 0;
//             $associatedMadrasa->qirat = $madrasaData['qirat'] ?? 0;

//             // File handling with type check
//             if (isset($madrasaData['noc_file']) && $madrasaData['noc_file'] instanceof \Illuminate\Http\UploadedFile) {
//                 $associatedMadrasa->noc_file = $madrasaData['noc_file']->store('markaz/associated/noc');
//             }

//             if (isset($madrasaData['resolution_file']) && $madrasaData['resolution_file'] instanceof \Illuminate\Http\UploadedFile) {
//                 $associatedMadrasa->resolution_file = $madrasaData['resolution_file']->store('markaz/associated/resolution');
//             }

//             $associatedMadrasa->save();
//         }
//     }

//     return redirect()->route('markaz-agreements.index')
//         ->with('success', 'মারকায চুক্তি সফলভাবে সংরক্ষণ করা হয়েছে');
// }

  public function store(Request $request, StoreMarkazAgreementService $service)
    {
        $service->execute($request, Auth::user());

        return redirect()->route('markaz-agreements.index')
            ->with('success', 'মারকায চুক্তি সফলভাবে সংরক্ষণ করা হয়েছে');
    }






    public function Edit($id)
    {
        // Get the main agreement with associated madrasas
        $markazAgreement = MarkazAgreement::with('associatedMadrasas')->findOrFail($id);

        // Add file URLs to main agreement
        $markazData = $markazAgreement->toArray();
        $markazData['noc_file_url'] = $markazAgreement->noc_file ? url('/storage/' . $markazAgreement->noc_file) : null;
        $markazData['resolution_file_url'] = $markazAgreement->resolution_file ? url('/storage/' . $markazAgreement->resolution_file) : null;
        $markazData['muhtamim_consent_url'] = $markazAgreement->muhtamim_consent ? url('/storage/' . $markazAgreement->muhtamim_consent) : null;
        $markazData['president_consent_url'] = $markazAgreement->president_consent ? url('/storage/' . $markazAgreement->president_consent) : null;
        $markazData['committee_resolution_url'] = $markazAgreement->committee_resolution ? url('/storage/' . $markazAgreement->committee_resolution) : null;
        
        // Also include the file paths for form initialization
        $markazData['noc_file'] = $markazAgreement->noc_file;
        $markazData['resolution_file'] = $markazAgreement->resolution_file;
        $markazData['muhtamim_consent'] = $markazAgreement->muhtamim_consent;
        $markazData['president_consent'] = $markazAgreement->president_consent;
        $markazData['committee_resolution'] = $markazAgreement->committee_resolution;

        // Add file URLs to associated madrasas
        $associatedMadrasas = $markazAgreement->associatedMadrasas->map(function ($madrasa) {
            return [
                'id' => $madrasa->id,
                'madrasa_Name' => $madrasa->madrasa_Name,
                'madrasa_id' => $madrasa->madrasa_id,
                'fazilat' => $madrasa->fazilat,
                'sanabiya_ulya' => $madrasa->sanabiya_ulya,
                'sanabiya' => $madrasa->sanabiya,
                'mutawassita' => $madrasa->mutawassita,
                'ibtedaiyyah' => $madrasa->ibtedaiyyah,
                'hifzul_quran' => $madrasa->hifzul_quran,
                'qirat' => $madrasa->qirat,
                'noc_file' => $madrasa->noc_file,
                'noc_file_url' => $madrasa->noc_file ? url('/storage/' . $madrasa->noc_file) : null,
                'resolution_file' => $madrasa->resolution_file,
                'resolution_file_url' => $madrasa->resolution_file ? url('/storage/' . $madrasa->resolution_file) : null,
            ];
        });

        return Inertia::render('Markaz/markaz_apply_edit', [
            'markazData' => $markazData,
            'associatedMadrasas' => $associatedMadrasas
        ]);
    }



        public function update(Request $request, $id)
    {
        $markazAgreement = MarkazAgreement::findOrFail($id);

        // Update main agreement data
        $markazAgreement->update([
            'madrasha_Name' => $request->madrasha_Name,
            'markaz_type' => $request->markaz_type,
            'fazilat' => $request->fazilat,
            'sanabiya_ulya' => $request->sanabiya_ulya,
            'sanabiya' => $request->sanabiya,
            'mutawassita' => $request->mutawassita,
            'ibtedaiyyah' => $request->ibtedaiyyah,
            'hifzul_quran' => $request->hifzul_quran,
            'qirat' => $request->qirat,
            'requirements' => $request->requirements,
        ]);

        // Handle main agreement files
        $fileFields = [
            'noc_file' => 'markaz/noc',
            'resolution_file' => 'markaz/resolution',
            'muhtamim_consent' => 'markaz/consent',
            'president_consent' => 'markaz/consent',
            'committee_resolution' => 'markaz/consent'
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                if ($markazAgreement->$field) {
                    Storage::delete($markazAgreement->$field);
                }
                $markazAgreement->$field = $request->file($field)->store($path, 'public');
            }
        }

        $markazAgreement->save();

        // Get IDs of existing associated madrasas
        $existingMadrasaIds = MarkazAgreementMadrasa::where('markaz_agreement_id', $id)
            ->pluck('id')
            ->toArray();

        // Get IDs of madrasas that should remain after update
        $updatedMadrasaIds = [];

        // Update or create associated madrasas
        foreach ($request->associated_madrasas as $madrasaData) {
            if (isset($madrasaData['id'])) {
                // Update existing madrasa
                $associatedMadrasa = MarkazAgreementMadrasa::find($madrasaData['id']);
                $updatedMadrasaIds[] = $madrasaData['id'];
            } else {
                // Create new madrasa
                $associatedMadrasa = new MarkazAgreementMadrasa();
                $associatedMadrasa->markaz_agreement_id = $id;
            }
            $associatedMadrasa->madrasa_Name = $madrasaData['madrasa_Name'];
            $associatedMadrasa->madrasa_id = $madrasaData['madrasa_id'] ?? null;
            $associatedMadrasa->fazilat = $madrasaData['fazilat'];
            $associatedMadrasa->sanabiya_ulya = $madrasaData['sanabiya_ulya'];
            $associatedMadrasa->sanabiya = $madrasaData['sanabiya'];
            $associatedMadrasa->mutawassita = $madrasaData['mutawassita'];
            $associatedMadrasa->ibtedaiyyah = $madrasaData['ibtedaiyyah'];
            $associatedMadrasa->hifzul_quran = $madrasaData['hifzul_quran'];
            $associatedMadrasa->qirat = $madrasaData['qirat'];

            // Handle file uploads
            if (isset($madrasaData['noc_file']) && $madrasaData['noc_file'] instanceof UploadedFile) {
                if ($associatedMadrasa->noc_file) {
                    Storage::delete($associatedMadrasa->noc_file);
                }
                $associatedMadrasa->noc_file = $madrasaData['noc_file']->store('markaz/associated/noc', 'public');
            }

            if (isset($madrasaData['resolution_file']) && $madrasaData['resolution_file'] instanceof UploadedFile) {
                if ($associatedMadrasa->resolution_file) {
                    Storage::delete($associatedMadrasa->resolution_file);
                }
                $associatedMadrasa->resolution_file = $madrasaData['resolution_file']->store('markaz/associated/resolution', 'public');
            }

            $associatedMadrasa->save();

            // Add the ID to updated list (for new records, add after save)
            if (!isset($madrasaData['id'])) {
                $updatedMadrasaIds[] = $associatedMadrasa->id;
            }
        }

        // Delete madrasas that are no longer in the request
        $madrasasToDelete = array_diff($existingMadrasaIds, $updatedMadrasaIds);
        MarkazAgreementMadrasa::whereIn('id', $madrasasToDelete)->delete();

        return redirect()->route('markaz-agreements.index')
            ->with('success', 'মারকায চুক্তি সফলভাবে আপডেট করা হয়েছে');
    }






 public function submitApplication($id)
    {
        // 1️⃣ First check markaz_agreements table
        $agreement = MarkazAgreement::find($id);
        if (!$agreement) {
            return back()->withErrors(['error' => 'আবেদন পাওয়া যায়নি!']);
        }

        // 2️⃣ Check if already submitted to avoid duplicate submissions
        if ($agreement->status === MarkazAgreementLog::STATUS_BOARD_SUBMITTED) {
            return back()->withErrors(['error' => 'আবেদন ইতিমধ্যে বোর্ডে দাখিল করা হয়েছে!']);
        }

        // 3️⃣ Now check schedule timing before anything else
        $schedule = schedule_setups::where('exam_setup_id', $agreement->exam_id)
            ->where('schedule_type', 'মারকায আবেদন')
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return back()->withErrors(['error' => 'সময়সূচী পাওয়া যায়নি!']);
        }

        $currentDate = now();
        $startDate = \Carbon\Carbon::parse($schedule->start_date);
        $endDate = \Carbon\Carbon::parse($schedule->end_date);

        if ($currentDate->lt($startDate)) {
            return back()->withErrors(['error' => 'আবেদনের সময় শুরু হয়নি! ' . $startDate->format('d-m-Y') . ' তারিখে আবার চেষ্টা করুন।']);
        }

        if ($currentDate->gt($endDate)) {
            return back()->withErrors(['error' => 'আবেদনের সময় শেষ! আগামী বছর আবার চেষ্টা করুন।']);
        }

        // 4️⃣ Submit to board using the status service
        try {
            $statusService = new MarkazStatusService();
            $statusService->submitToBoard($agreement->id, Auth::user()->id);
            
            return back()->with('success', 'আবেদন সফলভাবে বোর্ডে দাখিল হয়েছে!');
        } catch (\Exception $e) {
            Log::error('Markaz Submit Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'আবেদন দাখিলে সমস্যা হয়েছে! আবার চেষ্টা করুন। ' . $e->getMessage()]);
        }
    }





    // মাদরাসার আবেদন ডিলিট পার্ট

    public function deleteAgreement($id)
    {
        $agreement = MarkazAgreement::find($id);

        if (!$agreement) {
            return response()->json(['error' => 'আবেদন পাওয়া যায়নি!'], 404);
        }

        $agreement->delete();

        return redirect()->back()->with('success', 'আবেদন সফলভাবে মুছে ফেলা হয়েছে!');
    }





  public function viewDetails($id)
    {
        $markazDetails = MarkazAgreement::with([
            'associatedMadrasas',
            'activityLogs' => function ($query) {
                $query->select(
                    'id',
                    'markaz_agreement_id',
                    'user_name',
                    'admin_name',
                    'user_position',
                    'admin_position',
                    'admin_message',
                    'status',
                    'created_at'
                );
            }
        ])->select(
            'id',
            'madrasha_Name',
            'markaz_type',
            'created_at',
            'fazilat',
            'sanabiya_ulya',
            'sanabiya',
            'mutawassita',
            'ibtedaiyyah',
            'hifzul_quran',
            'qirat',
            'noc_file',
            'resolution_file',
            'requirements',
            'muhtamim_consent',
            'president_consent',
            'committee_resolution',
            'user_id',
            'exam_id',
            'exam_name'
        )->findOrFail($id);

        // Format dates and set URLs for files
        $markazDetails->created_at = $markazDetails->created_at->timestamp * 1000;
        $markazDetails->president_consent = $markazDetails->president_consent
            ? asset('storage/images/' . basename($markazDetails->president_consent))
            : null;
        $markazDetails->resolution_file = $markazDetails->resolution_file ? Storage::url($markazDetails->resolution_file) : null;

        // Map admin_position values to their text representations
        $markazDetails->activityLogs->each(function ($log) {
            $log->admin_position_text = match ((int)$log->admin_position) {
                1 => 'সুপার এডমিন',
                2 => 'সহ সুপার এডমিন',
                3 => 'বোর্ড এডমিন',
                default => $log->admin_position
            };
        });

        return inertia('Markaz/marjaz_detailes_view', [
            'markazDetails' => $markazDetails
        ]);
    }




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

}
