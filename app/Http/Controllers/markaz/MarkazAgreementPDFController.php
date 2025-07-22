<?php

namespace App\Http\Controllers\markaz;
use App\Models\MarkazAgreement;
use App\Models\MarkazAgreementMadrasa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mpdf\Mpdf;


class MarkazAgreementPDFController extends Controller
{

    public function generatePDF($id)
    {
        // Fetch the markaz agreement with associated madrasas
        $markazAgreement = MarkazAgreement::with('associatedMadrasas')->findOrFail($id);

        // Prepare data for the PDF view
        $application = [
            'application_no' => $markazAgreement->id,

            // Determine student type based on markaz_type
            'is_student' => $markazAgreement->markaz_type !== 'ছাত্রী',
            'is_female_student' => $markazAgreement->markaz_type === 'ছাত্রী',

            // Madrasa info
            'muhtamim_name' => $markazAgreement->madrasha_Name,
            'years_with_befaq' => 3, // Assuming a default value, replace with actual field if available
            'ilhok_no' => $markazAgreement->Elhaq_no,
            'madrasa_code' => $markazAgreement->madrasha_code,
            'madrasa_name' => $markazAgreement->madrasha_Name,
            'address' => $markazAgreement->division . ', ' . $markazAgreement->district . ', ' . $markazAgreement->thana_uni,
            'mobile' => '', // Add mobile number field if available
            'email' => '', // Add email field if available
            'previous_markaz_info' => '', // Add previous markaz info field if available
            'necessity_for_markaz' => $markazAgreement->requirements,

            // Associated madrasas
            'affiliated_madrasas' => $this->prepareAffiliatedMadrasas($markazAgreement->associatedMadrasas),

            // Calculate total examinees
            'total_examinees_applied_markaz' => $this->calculateTotalExaminees($markazAgreement),

            // Hall details - assuming these would be added in future updates
            'hall_description_checked' => true,
            'hall_length' => '50', // Example values, replace with actual fields if available
            'hall_width' => '30',
            'total_square_feet' => '1500',
            'tripod_count' => '50',

            // Attachment status - based on file presence
            'attachment_resolution' => !empty($markazAgreement->resolution_file),
            'attachment_previous_markaz_no_objection' => !empty($markazAgreement->noc_file),
            'attachment_befaq_recommendation' => !empty($markazAgreement->muhtamim_consent),
            'attachment_affiliated_madrasas_consent' => true, // Assuming true if there are affiliated madrasas
            'attachment_affiliated_madrasas_previous_no_objection' => true, // Assuming true

            // Mobile number and oath date
            'muhtamim_mobile' => '', // Add mobile number field if available
            'oath_date' => date('d/m/Y'), // Current date as default, replace with actual field if available
        ];

        // Generate HTML content from Blade view
        $htmlContent = view('markazApplication.markaz_application_pdf_mpdf', ['application' => $application])->render();

        try {
            // Configure mPDF with proper Bangla font support
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'default_font' => 'solaimanlipi',
                'fontDir' => [storage_path('fonts')],
                'fontdata' => [
                    'solaimanlipi' => [
                        'R' => 'SolaimanLipi.ttf',
                        'B' => 'SolaimanLipi.ttf',
                        'I' => 'SolaimanLipi.ttf',
                        'BI' => 'SolaimanLipi.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                    'kalpurush' => [
                        'R' => 'Kalpurush.ttf',
                        'B' => 'Kalpurush.ttf',
                        'I' => 'Kalpurush.ttf',
                        'BI' => 'Kalpurush.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                    'nikosh' => [
                        'R' => 'Nikosh.ttf',
                        'B' => 'Nikosh.ttf',
                        'I' => 'Nikosh.ttf',
                        'BI' => 'Nikosh.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ],
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'autoVietnamese' => true,
                'autoArabic' => true,
            ]);

            // Disable font substitution for better Bangla rendering
            $mpdf->useSubstitutions = false;
            $mpdf->simpleTables = true;

            // Set proper language and direction
            $mpdf->SetDirectionality('ltr');
            $mpdf->SetDefaultFont('solaimanlipi');

            // Write HTML content to PDF
            $mpdf->WriteHTML($htmlContent);

            // Generate filename
            $filename = 'markaz_application_' . $id . '.pdf';

            // Download the PDF
            return response($mpdf->Output($filename, 'D'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Prepare affiliated madrasas data for PDF
     */
    private function prepareAffiliatedMadrasas($madrasas)
    {
        $result = [];
        foreach ($madrasas as $madrasa) {
            $result[] = [
                'ilhok_madrasa_name_address' => $madrasa->madrasa_Name,
                'fazilot_sana_ulya' => $madrasa->fazilat + $madrasa->sanabiya_ulya,
                'mutawassita' => $madrasa->mutawassita,
                'ibtidaia' => $madrasa->ibtedaiyyah,
                'number_of_examinees' => $this->calculateMadrasaExaminees($madrasa),
                'total_examinees' => $this->calculateMadrasaExaminees($madrasa),
                'consent_letter' => !empty($madrasa->noc_file),
                'no_objection_letter' => !empty($madrasa->resolution_file),
            ];
        }
        return $result;
    }

    /**
     * Calculate total examinees for a madrasa
     */
    private function calculateMadrasaExaminees($madrasa)
    {
        return $madrasa->fazilat +
               $madrasa->sanabiya_ulya +
               $madrasa->sanabiya +
               $madrasa->mutawassita +
               $madrasa->ibtedaiyyah +
               $madrasa->hifzul_quran +
               $madrasa->qirat;
    }

    /**
     * Calculate total examinees for the entire markaz
     */
    private function calculateTotalExaminees($markazAgreement)
    {
        // Start with the main madrasa examinees
        $total = $markazAgreement->fazilat +
                 $markazAgreement->sanabiya_ulya +
                 $markazAgreement->sanabiya +
                 $markazAgreement->mutawassita +
                 $markazAgreement->ibtedaiyyah +
                 $markazAgreement->hifzul_quran +
                 $markazAgreement->qirat;

        // Add affiliated madrasas examinees
        foreach ($markazAgreement->associatedMadrasas as $madrasa) {
            $total += $this->calculateMadrasaExaminees($madrasa);
        }

        return $total;
    }
}
