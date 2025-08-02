<?php

namespace App\Http\Controllers\markaz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarkazAgreement;
use App\Models\admin\marhala_for_admin\ExamSetup;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\MarkazAgreement\StoreMarkazAgreementService;

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



  public function store(Request $request, StoreMarkazAgreementService $service)
    {
        $service->execute($request, Auth::user());

        return redirect()->route('markaz-agreements.index')
            ->with('success', 'মারকায চুক্তি সফলভাবে সংরক্ষণ করা হয়েছে');
    }






    public function Edit($id)
    {
        $result = app(\App\Services\MarkazAgreement\EditMarkazAgreementService::class)->execute($id);
        return \Inertia\Inertia::render('Markaz/markaz_apply_edit', [
            'markazData' => $result['markazData'],
            'associatedMadrasas' => $result['associatedMadrasas']
        ]);
    }



    public function update(\App\Http\Requests\MarkazAgreement\UpdateMarkazAgreementRequest $request, $id)
    {
        app(\App\Services\MarkazAgreement\UpdateMarkazAgreementService::class)->execute($request, $id);
        return redirect()->back()->with('success', 'মারকায চুক্তি সফলভাবে আপডেট করা হয়েছে');
    }








    public function submitApplication($id)
    {
        $result = app(\App\Services\MarkazAgreement\SubmitMarkazAgreementService::class)->execute($id, Auth::user()->id);
        if (isset($result['error'])) {
            return back()->withErrors(['error' => $result['error']]);
        }
        return back()->with('success', $result['success']);
    }





    // মাদরাসার আবেদন ডিলিট পার্ট

    public function deleteAgreement($id)
    {
        $result = app(\App\Services\MarkazAgreement\DeleteMarkazAgreementService::class)->execute($id);
        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 404);
        }
        return redirect()->back()->with('success', $result['success']);
    }





    public function viewDetails($id)
    {
        $markazDetails = app(\App\Services\MarkazAgreement\ViewMarkazAgreementDetailsService::class)->execute($id);
        return inertia('Markaz/marjaz_detailes_view', [
            'markazDetails' => $markazDetails
        ]);
    }









        public function fatch()
    {
        $agreements = MarkazAgreement::with(['associatedMadrasas', 'mainMadrasa:id,MName,ElhaqNo', 'examSetup'])
            ->latest()
            ->get()
            ->map(function ($agreement) {
                // Fetch main madrasa name and ElhaqNo from mainMadrasa relation, fallback to madrasha_Name
                $mainMadrasa = $agreement->mainMadrasa;
                $madrashaName = $mainMadrasa->MName ?? $agreement->madrasha_Name;
                $mainElhaqNo = $mainMadrasa->ElhaqNo ?? null;
                $examName = $agreement->examSetup ? $agreement->examSetup->exam_name : null;
                $status = $agreement->status ?? 'পেন্ডিং';
                return [
                    'id' => $agreement->id,
                    'number' => $agreement->id,
                    'Elhaq_no' => $agreement->Elhaq_no ?? $mainElhaqNo,
                    'madrasha_Name' => $madrashaName, // <-- Fix: use this key for DataTable
                    'main_madrasa_elhaq_no' => $mainElhaqNo,
                    'markaz_type' => $agreement->markaz_type,
                    'exam_name' => $examName,
                    'status' => $status,
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
                    'boardStatus' => 'Pending'
                ];
            });

        return response()->json($agreements);
    }




public function getTableData()
{
    $agreements = app(\App\Services\MarkazAgreement\ViewMarkazAgreementTableDataService::class)
        ->execute(Auth::id());
    return response()->json([
        'agreements' => $agreements
    ]);
}



}
