<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ComprehensivePermissionsSeeder extends Seeder
{
    public function run()
    {
        // সাইডবার থেকে সব permission নাম সংগ্রহ করা হয়েছে
        $permissions = [
            // Setup permissions (already exist)
            'setup.access',
            'setup.marhala',
            'setup.subject',
            'setup.central_exam',
            'setup.user',
            'setup.instruction',
            
            // Bill permissions (already exist)
            'bill.access',
            'bill.negran',
            'bill.mumtahin',
            'bill.markaz_admin',
            'bill.zonal',
            
            // Madrasa permissions (already exist)
            'madrasa.access',
            'madrasa.list',
            'madrasa.misc',
            'madrasa.admin',
            'madrasa.draft_soft_delete',
            'madrasa.payment',
            
            // Markaz permissions (already exist)
            'markaz.access',
            'markaz.application_list',
            'markaz.proposed',
            'markaz.list',
            'markaz.change_application',
            'markaz.admin',
            'markaz.admin_training',
            'markaz.group',
            
            // Exam Routine permissions
            'exam_routine.access',
            'exam_routine.group',
            'exam_routine.routine',
            'exam_routine.oral_exam_mumtahin',
            
            // Registration permissions (already exist)
            'registration.access',
            'registration.list',
            'registration.payment',
            'registration.card_create',
            
            // Inclusion permissions (already exist)
            'inclusion.access',
            'inclusion.list',
            'inclusion.roll_generate',
            'inclusion.admit_card',
            'inclusion.payment',
            'inclusion.cancelled_list',
            
            // Khata Loose permissions
            'khata_loose.access',
            'khata_loose.setup',
            'khata_loose.generate',
            'khata_loose.account',
            'khata_loose.others',
            
            // Negran permissions
            'negran.access',
            'negran.application_list',
            'negran.proposed_list',
            'negran.pending_list',
            'negran.cancelled_list',
            'negran.mumtahin_list',
            'negran.report',
            'negran.allowance',
            
            // Mumtahin permissions
            'mumtahin.access',
            'mumtahin.application_list',
            'mumtahin.proposed_list',
            'mumtahin.inspector_setup',
            'mumtahin.list',
            'mumtahin.darsiyat',
            'mumtahin.hifz_selection',
            'mumtahin.qirat_selection',
            'mumtahin.training',
            
            // Zone permissions
            'zone.access',
            'zone.setup',
            'zone.selection',
            'zone.sub_zone_setup',
            'zone.markaz_setup',
            
            // Attendance permissions
            'attendance.access',
            'attendance.examinee',
            'attendance.negran',
            
            // Result permissions (already exist)
            'result.access',
            'result.condition',
            'result.correction',
            'result.review',
            'result.merit_condition',
            'result.inspection_formula',
            'result.darsiyat_result',
            'result.oral_result',
            'result.review_correction',
            
            // Others
            'messaging',
            'notice',
            'role.manage',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Give all permissions to super admin
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        $this->command->info('All permissions added successfully!');
    }
}
