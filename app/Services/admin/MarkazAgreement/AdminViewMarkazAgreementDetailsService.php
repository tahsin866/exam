<?php

namespace App\Services\admin\MarkazAgreement;

use App\Models\MarkazAgreement;
use Illuminate\Support\Facades\Storage;

class AdminViewMarkazAgreementDetailsService
{
    public function execute($id)
    {
        $markazDetails = MarkazAgreement::with([
            'associatedMadrasas',
            'mainMadrasa:id,MName',
            'examSetup:id,exam_name',
            'creator.roles',
            'approver.roles',
            'logs.user.roles'
        ])->select(
            'id',
            'madrasha_id',
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
            'created_by',
            'approved_by',
            'approved_at',
            'admin_feedback',
            'feedback_image',
            'status',
            'processed_at',
            'admin_name',

        )->findOrFail($id);

        // Store original created_at for later use
        $originalCreatedAt = $markazDetails->created_at;
        // Format dates and set URLs for files
        $markazDetails->created_at = $markazDetails->created_at->timestamp * 1000;
        $markazDetails->president_consent = $markazDetails->president_consent
            ? asset('storage/images/' . basename($markazDetails->president_consent))
            : null;
        $markazDetails->resolution_file = $markazDetails->resolution_file ? Storage::url($markazDetails->resolution_file) : null;

        // Main madrasa name (from relation)
        $markazDetails->main_madrasa_name = $markazDetails->mainMadrasa?->MName;
        // Exam name (from relation)
        $markazDetails->exam_name = $markazDetails->examSetup?->exam_name;

        // Build comprehensive status logs from markaz_agreement_logs table
        $status_logs = [];

        // Get logs from markaz_agreement_logs table
        foreach ($markazDetails->logs as $log) {
            $user = $log->user;
            $userDesignation = null;

            if ($user) {
                if ($user->user_type === 'madrasa') {
                    $userDesignation = $user->admin_Designation ?? 'মাদরাসা ব্যবহারকারী';
                } elseif ($user->user_type === 'admin') {
                    $role = $user->roles?->first();
                    if ($role) {
                        // Map English role names to Bangla
                        $userDesignation = match($role->name) {
                            'super-admin' => 'সুপার এডমিন',
                            'assistant-super-admin' => 'সহ সুপার এডমিন',
                            'board-admin' => 'বোর্ড এডমিন',
                            default => $role->name ?? 'এডমিন'
                        };
                    } else {
                        $userDesignation = $user->admin_Designation ?? 'এডমিন';
                    }
                } else {
                    $userDesignation = $user->admin_Designation ?? 'N/A';
                }

                // Get status in Bangla
                $statusBangla = match($log->new_status) {
                    'pending' => 'পেন্ডিং',
                    'approved' => 'অনুমোদিত',
                    'suspended' => 'স্থগিত',
                    'cancelled' => 'বাতিল',
                    'board_returned' => 'বোর্ড ফেরত',
                    'board_submitted' => 'বোর্ড দাখিল',
                    'in_processing' => 'প্রক্রিয়াধীন',
                    default => 'অজানা'
                };

                // Get feedback image from markaz_agreements table if this log relates to admin feedback
                $feedbackImage = null;
                if (in_array($log->new_status, ['board_returned', 'suspended', 'cancelled']) && $markazDetails->feedback_image) {
                    $feedbackImage = asset('storage/' . $markazDetails->feedback_image);
                }

                $status_logs[] = [
                    'name' => $user->name,
                    'designation' => $userDesignation,
                    'user_type' => $user->user_type,
                    'status' => $statusBangla,
                    'comments' => $log->comments,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    'feedback_image' => $feedbackImage,
                    'action_type' => $log->action_type,
                    'previous_status' => $log->previous_status,
                    'new_status' => $log->new_status,
                ];
            }
        }

        // Sort logs by created_at descending (newest first)
        usort($status_logs, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $markazDetails->status_logs = $status_logs;

        // সিদ্ধান্ত গ্রহণকারী (created_by, approved_by)
        $createdByDesignation = null;
        if ($markazDetails->creator) {
            if ($markazDetails->creator->user_type === 'admin') {
                $role = $markazDetails->creator->roles?->first();
                $createdByDesignation = $role ? match($role->name) {
                    'super-admin' => 'সুপার এডমিন',
                    'assistant-super-admin' => 'সহ সুপার এডমিন',
                    'board-admin' => 'বোর্ড এডমিন',
                    default => $role->name ?? 'এডমিন'
                } : 'এডমিন';
            } else {
                $createdByDesignation = $markazDetails->creator->admin_Designation ?? 'মাদরাসা ব্যবহারকারী';
            }
        }

        $approvedByDesignation = null;
        if ($markazDetails->approver) {
            if ($markazDetails->approver->user_type === 'admin') {
                $role = $markazDetails->approver->roles?->first();
                $approvedByDesignation = $role ? match($role->name) {
                    'super-admin' => 'সুপার এডমিন',
                    'assistant-super-admin' => 'সহ সুপার এডমিন',
                    'board-admin' => 'বোর্ড এডমিন',
                    default => $role->name ?? 'এডমিন'
                } : 'এডমিন';
            } else {
                $approvedByDesignation = $markazDetails->approver->admin_Designation ?? 'মাদরাসা ব্যবহারকারী';
            }
        }

        $markazDetails->decision_makers = [
            'created_by' => [
                'name' => $markazDetails->creator?->name,
                'designation' => $createdByDesignation,
            ],
            'approved_by' => [
                'name' => $markazDetails->approver?->name,
                'designation' => $approvedByDesignation,
            ],
        ];

        // তারিখ ও সময়
        $markazDetails->created_at_formatted = $originalCreatedAt ? $originalCreatedAt->format('Y-m-d H:i:s') : null;
        $markazDetails->approved_at_formatted = $markazDetails->approved_at ? $markazDetails->approved_at->format('Y-m-d H:i:s') : null;

        // মন্তব্য (admin_feedback)
        $markazDetails->admin_feedback = $markazDetails->admin_feedback;

        return $markazDetails;
    }
}
