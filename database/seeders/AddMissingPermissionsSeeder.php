<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddMissingPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Define missing permissions
        $permissions = [
            // Exam Routine
            'exam_routine.access',
            'exam_routine.group',
            'exam_routine.routine',
            'exam_routine.oral_exam_mumtahin',
            
            // Registration
            'registration.madrasa_list',
            'registration.cancelled_list',
            'registration.payment_list',
            'registration.board_return_list',
            
            // Inclusion
            'inclusion.cancelled_list',
            
            // Khata Loose
            'khata_loose.access',
            'khata_loose.setup',
            'khata_loose.generate',
            'khata_loose.account',
            'khata_loose.others',
            
            // Negran
            'negran.access',
            'negran.application_list',
            'negran.proposed_list',
            'negran.pending_list',
            'negran.cancelled_list',
            'negran.mumtahin_list',
            'negran.report',
            'negran.allowance',
            
            // Mumtahin
            'mumtahin.access',
            'mumtahin.application_list',
            'mumtahin.proposed_list',
            'mumtahin.inspector_setup',
            'mumtahin.list',
            'mumtahin.darsiyat',
            'mumtahin.hifz_selection',
            'mumtahin.qirat_selection',
            'mumtahin.training',
            
            // Zone
            'zone.access',
            'zone.setup',
            'zone.selection',
            'zone.sub_zone_setup',
            'zone.markaz_setup',
            
            // Attendance
            'attendance.access',
            'attendance.examinee',
            'attendance.negran',
            
            // Result
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

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Give all permissions to super admin
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        $this->command->info('Missing permissions added successfully!');
    }
}
