<?php

namespace App\Http\Controllers;
use App\Models\madrasha;
use Inertia\Inertia;
use App\Models\Madrasa;
use App\Models\Division;
use App\Models\District;
use App\Models\Thana;
use Illuminate\Http\Request;
use App\Exports\MadrasaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\reg_stu_information;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MadrashaController extends Controller
{
    //
    // public function getMadrashaList(Request $request)
    // {
    //     $perPage = $request->input('per_page', 10);
    //     $page = $request->input('page', 1);

    //     $applications = Madrasha::query()
    //         ->select(/* your fields */)
    //         ->paginate($perPage);

    //     // Calculate proper from-to values
    //     $applications->from = ($page - 1) * $perPage + 1;
    //     $applications->to = min($page * $perPage, $applications->total());


    //     $applications = madrasha::select(
    //         'id',
    //         'MName as name',
    //         'ElhaqNo as Elhaq_no',
    //         'MType',
    //         'markaz_serial',
    //         'Mobile as mobile_no',
    //         'DID',
    //         // 'district',
    //         // 'Thana_uni as thana'
    //     )->paginate($perPage);

    //     $applications->getCollection()->transform(function ($app) {
    //         $app->MType = $app->MType == 0 ? 'ছাত্রী' : 'ছাত্র';
    //         return $app;
    //     });

    //     return response()->json($applications);
    // }








    public function getMadrashaList(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $page = $request->input('page', 1);

        $applications = Madrasha::with(['division', 'district', 'thana'])
            ->select(
                'id',
                'MName as name',
                'ElhaqNo as Elhaq_no',
                'MType',
                'markaz_serial',
                'Mobile as mobile_no',
                'DID',
                'DISID',
                'TID'
            )
            ->paginate($perPage);

        // Calculate proper from-to values
        $applications->from = ($page - 1) * $perPage + 1;
        $applications->to = min($page * $perPage, $applications->total());

        $applications->getCollection()->transform(function ($app) {
            $app->MType = $app->MType == 0 ? 'ছাত্রী' : 'ছাত্র';
            $app->division_name = $app->division ? $app->division->Division : null;
            $app->district_name = $app->district ? $app->district->District : null;
            $app->thana_name = $app->thana ? $app->thana->Thana : null;

            // রিলেশনশিপ অবজেক্টগুলো অপসারণ করে শুধু নামগুলো রাখা
            unset($app->division);
            unset($app->district);
            unset($app->thana);

            return $app;
        });

        return response()->json($applications);
    }



// Page method for all markaz list
public function allMarkazListPage(Request $request)
{
    try {
        // Get initial markaz data with dynamic per_page
        $perPage = $request->input('per_page', 10);
        $query = DB::table('madrasha_centers as mc')
            ->join('schools as s', 'mc.madrasha_id', '=', 's.madrasha_id')
            ->leftJoin('division as d', 's.DID', '=', 'd.id')
            ->leftJoin('district as dt', 's.DES_ID', '=', 'dt.DesID')
            ->leftJoin('thana as t', 's.TID', '=', 't.Thana_ID')
            ->select(
                'mc.id as center_id',
                'mc.center_id as center_type_id',
                'mc.serial_number',
                's.MName as name',
                's.ElhaqNo as Elhaq_no',
                's.MType',
                's.Mobile as mobile_no',
                's.DID',
                's.DES_ID as DISID',
                's.TID',
                'd.Division as division_name',
                'dt.District as district_name',
                't.Thana as thana_name'
            )
            ->orderBy('mc.id', 'desc');

        $initialApplications = $query->paginate($perPage);

        // Transform data
        $initialApplications->getCollection()->transform(function ($item) {
            $item->MType = $item->MType == 0 ? 'ছাত্রী' : 'ছাত্র';
            return $item;
        });

        // Get divisions for dropdowns
        $divisions = Division::select('id', 'Division as Division')->get();

        return Inertia::render('markaz_for_admin/all_markaz_list', [
            'initialApplications' => $initialApplications,
            'initialDivisions' => $divisions,
            'initialDistricts' => [],
            'initialThanas' => [],
        ]);
    } catch (\Exception $e) {
        // Fallback with empty data
        return Inertia::render('markaz_for_admin/all_markaz_list', [
            'initialApplications' => [
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
                'from' => 0,
                'to' => 0
            ],
            'initialDivisions' => [],
            'initialDistricts' => [],
            'initialThanas' => [],
        ]);
    }
}

// API method for markaz list
public function getMarkazList(Request $request)
{
    $perPage = $request->input('per_page', 10);
    $page = $request->input('page', 1);

    Log::info('API getMarkazList called', [
        'per_page' => $perPage,
        'page' => $page,
        'filters' => $request->all()
    ]);

    $query = DB::table('madrasha_centers as mc')
        ->join('schools as s', 'mc.madrasha_id', '=', 's.madrasha_id')
        ->leftJoin('division as d', 's.DID', '=', 'd.id')
        ->leftJoin('district as dt', 's.DES_ID', '=', 'dt.DesID')
        ->leftJoin('thana as t', 's.TID', '=', 't.Thana_ID')
        ->select(
            'mc.id as center_id',
            'mc.center_id as center_type_id',
            'mc.serial_number',
            's.MName as name',
            's.ElhaqNo as Elhaq_no',
            's.MType',
            's.Mobile as mobile_no',
            's.DID',
            's.DES_ID as DISID',
            's.TID',
            'd.Division as division_name',
            'dt.District as district_name',
            't.Thana as thana_name'
        );

    // Apply filters
    if ($request->filled('madrasahName')) {
        $searchTerm = $request->madrasahName;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('s.MName', 'like', '%' . $searchTerm . '%')
              ->orWhere('s.ElhaqNo', 'like', '%' . $searchTerm . '%');
        });
    }

    // মাদরাসার ধরন ফিল্টার
    if ($request->filled('type')) {
        if ($request->type === 'ছাত্র') {
            $query->where('s.MType', 1);
        } elseif ($request->type === 'ছাত্রী') {
            $query->where('s.MType', 0);
        }
    }

    // বিভাগ ফিল্টার
    if ($request->filled('division')) {
        $query->where('s.DID', $request->division);
    }

    // জেলা ফিল্টার
    if ($request->filled('district')) {
        $query->where('s.DES_ID', $request->district);
    }

    // থানা ফিল্টার
    if ($request->filled('thana')) {
        $query->where('s.TID', $request->thana);
    }

    $query->orderBy('mc.id', 'desc');

    $data = $query->paginate($perPage, ['*'], 'page', $page);

    // Transform data
    $data->getCollection()->transform(function ($item) {
        $item->MType = $item->MType == 0 ? 'ছাত্রী' : 'ছাত্র';
        return $item;
    });

    Log::info('API response data', [
        'total' => $data->total(),
        'per_page' => $data->perPage(),
        'current_page' => $data->currentPage(),
        'data_count' => $data->count()
    ]);

    return response()->json($data);
}


// এডমিনের মারকায ফিল্টার


public function filterMadrashas(Request $request)
{
    $query = Madrasha::query();

    // Always apply the markaz_serial condition first
    $query->whereNotNull('markaz_serial')
          ->where('markaz_serial', '!=', '');

    // মাদরাসার নাম/ইলহাক নম্বর ফিল্টার
    if ($request->filled('madrasahName')) {
        $searchTerm = $request->madrasahName;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('MName', 'like', '%' . $searchTerm . '%')
              ->orWhere('ElhaqNo', 'like', '%' . $searchTerm . '%');
        });
    }

    // মাদরাসার ধরন ফিল্টার
    if ($request->filled('type')) {
        if ($request->type === 'ছাত্র') {
            $query->where('MType', 1);
        } elseif ($request->type === 'ছাত্রী') {
            $query->where('MType', 0);
        }
    }

    // মাদরাসার স্তর ফিল্টার
    if ($request->filled('level')) {
        switch ($request->level) {
            case 'takmil':
                $query->where('Stage', 1);
                break;
            case 'fazilat':
                $query->where('Stage', 2);
                break;
            case 'sanabiya_uliya':
                $query->where('Stage', 3);
                break;
            case 'sanabiya':
                $query->where('Stage', 4);
                break;
            case 'mutawassita':
                $query->where('Stage', 5);
                break;
            case 'ibtedaiya':
                $query->where('Stage', 6);
                break;
            case 'hifzul_quran':
                $query->where('Stage', 7);
                break;
            case 'ilmul_qiraat':
                $query->where('Stage', 8);
                break;
        }
    }

    // মারকাযের ধরন ফিল্টার
    if ($request->filled('status')) {
        if ($request->status === 'darsiyat') {
            $query->where('CenterD', 1);
        } elseif ($request->status === 'hifzul_quran') {
            $query->where('CenterH', 1);
        } elseif ($request->status === 'ilmul_qiraat') {
            $query->where('CenterK', 1);
        } elseif ($request->status === 'all') {
            $query->where(function ($q) {
                $q->where('CenterD', 1)
                  ->orWhere('CenterH', 1)
                  ->orWhere('CenterK', 1);
            });
        }
    }

    // বিভাগ ফিল্টার
    if ($request->filled('division')) {
        $query->where('DID', $request->division);
    }

    // জেলা ফিল্টার
    if ($request->filled('district')) {
        $query->where('DISID', $request->district);
    }

    // থানা ফিল্টার
    if ($request->filled('thana')) {
        $query->where('TID', $request->thana);
    }

    // ডেটা লোড
    $madrashas = $query->with(['division', 'district', 'thana'])->get();

    // ফরম্যাটিং
    $madrashas = $madrashas->map(function ($madrasha) {
        $madrasha->type = $madrasha->MType == 0 ? 'ছাত্রী' : 'ছাত্র';
        $madrasha->division_name = $madrasha->division ? $madrasha->division->Division : null;
        $madrasha->district_name = $madrasha->district ? $madrasha->district->District : null;
        $madrasha->thana_name = $madrasha->thana ? $madrasha->thana->Thana : null;

        // রিলেশনশিপ অবজেক্টগুলো অপসারণ
        unset($madrasha->division);
        unset($madrasha->district);
        unset($madrasha->thana);

        return $madrasha;
    });

    return response()->json($madrashas);
}


// filter markaz for studetn registration

public function filterMarkazStudents(Request $request)
{
    $self = $this;

    $query = reg_stu_information::select(
        'reg_stu_informations.markaz_id as id',
        'madrasha.MName as madrasha_Name',
        'madrasha.ElhaqNo as Elhaq_no',
        'madrasha.Mobile as Mobile',
        'madrasha.DID',
        'madrasha.DISID',
        'madrasha.TID',
        'division.Division as division_name',
        'district.District as district_name',
        'thana.Thana as thana_name',
        'reg_stu_informations.exam_name_Bn',
        'madrasha.markaz_serial as madrasha_code' // Add this line to get the code directly
    )
    ->selectRaw('COUNT(reg_stu_informations.id) as student_count')
    ->leftJoin('madrasha', 'reg_stu_informations.markaz_id', '=', 'madrasha.id')
    ->leftJoin('division', 'madrasha.DID', '=', 'division.id')
    ->leftJoin('district', 'madrasha.DISID', '=', 'district.DesID')
    ->leftJoin('thana', 'madrasha.TID', '=', 'thana.Thana_ID')
    ->whereNotNull('reg_stu_informations.markaz_id');

    // মাদরাসার নাম/ইলহাক নম্বর ফিল্টার
    if ($request->filled('madrasahName')) {
        $searchTerm = $request->madrasahName;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('madrasha.MName', 'like', '%' . $searchTerm . '%')
              ->orWhere('madrasha.ElhaqNo', 'like', '%' . $searchTerm . '%');
        });
    }

    // বিভাগ ফিল্টার
    if ($request->filled('division')) {
        $query->where('madrasha.DID', $request->division);
    }

    // জেলা ফিল্টার
    if ($request->filled('district')) {
        $query->where('madrasha.DISID', $request->district);
    }

    // থানা ফিল্টার
    if ($request->filled('thana')) {
        $query->where('madrasha.TID', $request->thana);
    }

    // পরীক্ষার নাম ফিল্টার
    if ($request->filled('exam_name')) {
        $query->where('reg_stu_informations.exam_name_Bn', $request->exam_name);
    }

    // মাদরাসার ধরন ফিল্টার
    if ($request->filled('type')) {
        if ($request->type === 'ছাত্র') {
            $query->where('madrasha.MType', 1);
        } elseif ($request->type === 'ছাত্রী') {
            $query->where('madrasha.MType', 0);
        }
    }

    // Group by the same fields as in the original query
    $students = $query->groupBy(
        'reg_stu_informations.markaz_id',
        'reg_stu_informations.exam_name_Bn',
        'madrasha.MName',
        'madrasha.ElhaqNo',
        'madrasha.Mobile',
        'madrasha.DID',
        'madrasha.DISID',
        'madrasha.TID',
        'division.Division',
        'district.District',
        'thana.Thana',
        'madrasha.markaz_serial' // Add this to the group by clause
    )
    ->havingRaw('COUNT(reg_stu_informations.id) > 0')
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'madrasha_Name' => $item->madrasha_Name,
            'Elhaq_no' => $item->Elhaq_no,
            'Mobile' => $item->Mobile,
            'DID' => $item->DID,
            'DISID' => $item->DISID,
            'TID' => $item->TID,
            'division_name' => $item->division_name,
            'district_name' => $item->district_name,
            'thana_name' => $item->thana_name,
            'exam_name_Bn' => $item->exam_name_Bn,
            'student_count' => $item->student_count,
            'madrasha_code' => $item->madrasha_code // Use the directly selected field
        ];
    });

    return response()->json($students);
}







// Page render method
public function madrashaListUnderMarkazPage($markazId)
{
    // madrasha_under_centers থেকে নির্দিষ্ট markazId অনুযায়ী চাইল্ড মাদরাসা গুলো আনবে
    $madrashaList = DB::table('madrasha_under_centers as muc')
        ->join('schools as s', 'muc.child_madrasha_id', '=', 's.madrasha_id')
        ->join('centers as c', 'muc.center_id', '=', 'c.id')
        ->where('muc.parent_madrasha_id', $markazId)
        ->select(
            's.madrasha_id as id',
            's.MNName as name',
            's.ElhaqNo as elhaq_no',
            's.mobile as mobile_no',
            'c.name as center_name'
        )
        ->get();

    return response()->json([
        'markaz_id' => $markazId,
        'total' => $madrashaList->count(),
        'madrashas' => $madrashaList
    ]);
}


// API method for AJAX calls
public function getMadrashasUnderMarkaz($markazId)
{
    Log::info('getMadrashasUnderMarkaz called with markazId: ' . $markazId);

    // First check if the tables exist and have data
    $mucCount = DB::table('madrasha_under_centers')->count();
    $schoolsCount = DB::table('schools')->count();
    $centersCount = DB::table('centers')->count();

    Log::info('Table counts - madrasha_under_centers: ' . $mucCount . ', schools: ' . $schoolsCount . ', centers: ' . $centersCount);

    // Check if there are any records for this markaz
    $specificMucCount = DB::table('madrasha_under_centers')
        ->where('parent_madrasha_id', $markazId)
        ->count();

    Log::info('Records for markaz ' . $markazId . ': ' . $specificMucCount);

    $madrashaList = DB::table('madrasha_under_centers as muc')
        ->join('schools as s', 'muc.child_madrasha_id', '=', 's.madrasha_id')
        ->join('centers as c', 'muc.center_id', '=', 'c.id')
        ->where('muc.parent_madrasha_id', $markazId)
        ->select(
            's.madrasha_id as id',
            's.MName as name',
            's.ElhaqNo as Elhaq_no',
            's.Mobile as Mobile_no',
            'c.name as center_name'
        )
        ->get();

    Log::info('Query result count: ' . $madrashaList->count());
    Log::info('Query result: ' . json_encode($madrashaList));

    return response()->json([
        'madrashas' => $madrashaList,
        'markaz_id' => $markazId,
        'total' => $madrashaList->count()
    ]);
}















    public function index(Request $request)
    {
        $query = madrasha::query()
            ->select('madrashas.*', 'divisions.divisionUni', 'districts.District_U', 'thanas.Thana_U')
            ->leftJoin('divisions', 'madrashas.division_id', '=', 'divisions.id')
            ->leftJoin('districts', 'madrashas.district_id', '=', 'districts.Des_ID')
            ->leftJoin('thanas', 'madrashas.thana_id', '=', 'thanas.id');

        if ($request->searchTerm) {
            $query->where(function($q) use ($request) {
                $q->where('MName_uni', 'LIKE', '%' . $request->searchTerm . '%')
                  ->orWhere('ElhaqNo', 'LIKE', '%' . $request->searchTerm . '%');
            });
        }

        if ($request->type) {
            $query->where('MType', $request->type);
        }

        if ($request->stage) {
            $query->where('Stage', $request->stage);
        }

        if ($request->division) {
            $query->where('madrashas.division_id', $request->division);
        }

        if ($request->district) {
            $query->where('madrashas.district_id', $request->district);
        }

        if ($request->thana) {
            $query->where('madrashas.thana_id', $request->thana);
        }

        return $query->paginate($request->per_page ?? 10);
    }

    public function getDivisions()
    {
        // Shows: ID=3, Division_U="সিলেট"
        return Division::select('id', 'Division as Division')->get();
    }

    public function getDistricts($divisionId)
    {
        // Shows districts where DID=3 (Sylhet Division)
        // Example: District_U="সুনামগঞ্জ", "মৌলভীবাজার", "হবিগঞ্জ", "সিলেট"
        return District::select('DesID', 'District')
            ->where('DID', $divisionId)  // DID=3 for Sylhet
            ->get();
    }

    public function getThanas($district_Id)
    {
        return Thana::select('Thana_ID', 'Thana')
            ->where('Des_ID', $district_Id)  // This matches thana.Des_ID with district.DesID
            ->get();
    }











    public function showMarlaZCenters()
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















    }
















