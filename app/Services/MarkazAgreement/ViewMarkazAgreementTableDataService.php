<?php

namespace App\Services\MarkazAgreement;

use App\Models\MarkazAgreement;
use Illuminate\Support\Facades\Auth;

class ViewMarkazAgreementTableDataService
{
    public function execute($userId)
    {
        $agreements = MarkazAgreement::with([
                'associatedMadrasas',
                'mainMadrasa:id,MName',
                'examSetup:id,exam_name,arabic_year,english_year'
            ])
            ->where('user_id', $userId)
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

                $mainMadrasaName = $agreement->mainMadrasa->MName ?? $agreement->madrasha_Name;
                $exam = $agreement->examSetup;
                $examLabel = $exam
                    ? "{$exam->exam_name} ({$exam->english_year} ইং / {$exam->arabic_year} হি:)"
                    : $agreement->exam_name;

                // Status mapping
                $statusMap = [
                    'board_submitted' => 'বোর্ড দাখিল',
                    'pending' => 'অপেক্ষমান',
                    'approved' => 'অনুমোদন করা হয়েছে',
                    'suspended' => 'স্থগিত করা হয়েছে',
                    'cancelled' => 'বাতিল করা হয়েছে',
                    'board_returned' => 'বোর্ড ফেরত',
                ];
                $status = $agreement->status ?? 'pending';
                $statusBn = $statusMap[$status] ?? $status;

                return [
                    'id' => $agreement->id,
                    'application_date' => $agreement->created_at->format('d/m/Y'),
                    'markaz_type' => $agreement->markaz_type,
                    'main_madrasa' => $mainMadrasaName,
                    'exam_name' => $examLabel,
                    'associated_madrasas' => $agreement->associatedMadrasas->pluck('madrasa_Name'),
                    'main_total_students' => $agreement->total_students,
                    'associated_total_students' => $associatedMadrasasTotal,
                    'status' => $statusBn
                ];
            });

        return $agreements;
    }
}
