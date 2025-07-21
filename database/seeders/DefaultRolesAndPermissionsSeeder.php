<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionModule;

class DefaultRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permission modules
        $modules = [
            [
                'name' => 'Setup Management',
                'name_bn' => 'সেটাপ সংক্রান্ত',
                'slug' => 'setup',
                'description' => 'System setup and configuration',
                'description_bn' => 'সিস্টেম সেটাপ এবং কনফিগারেশন',
                'icon' => 'pi pi-cog',
                'sort_order' => 1
            ],
            [
                'name' => 'Bill Management',
                'name_bn' => 'ভাতা ও বিল',
                'slug' => 'bill',
                'description' => 'Allowance and bill management',
                'description_bn' => 'ভাতা এবং বিল ব্যবস্থাপনা',
                'icon' => 'pi pi-money-bill',
                'sort_order' => 2
            ],
            [
                'name' => 'Madrasa Management',
                'name_bn' => 'মাদরাসা সংক্রান্ত',
                'slug' => 'madrasa',
                'description' => 'Madrasa related operations',
                'description_bn' => 'মাদরাসা সংক্রান্ত কার্যক্রম',
                'icon' => 'pi pi-building',
                'sort_order' => 3
            ],
            [
                'name' => 'Markaz Management',
                'name_bn' => 'মারকায সংক্রান্ত',
                'slug' => 'markaz',
                'description' => 'Markaz related operations',
                'description_bn' => 'মারকায সংক্রান্ত কার্যক্রম',
                'icon' => 'pi pi-map-marker',
                'sort_order' => 4
            ],
            [
                'name' => 'Exam Routine',
                'name_bn' => 'পরীক্ষার রুটিন',
                'slug' => 'exam_routine',
                'description' => 'Exam routine management',
                'description_bn' => 'পরীক্ষার রুটিন ব্যবস্থাপনা',
                'icon' => 'pi pi-calendar',
                'sort_order' => 5
            ],
            [
                'name' => 'Registration',
                'name_bn' => 'নিবন্ধন',
                'slug' => 'registration',
                'description' => 'Student registration management',
                'description_bn' => 'শিক্ষার্থী নিবন্ধন ব্যবস্থাপনা',
                'icon' => 'pi pi-user-plus',
                'sort_order' => 6
            ],
            [
                'name' => 'Inclusion',
                'name_bn' => 'অন্তর্ভুক্তি',
                'slug' => 'inclusion',
                'description' => 'Student inclusion management',
                'description_bn' => 'শিক্ষার্থী অন্তর্ভুক্তি ব্যবস্থাপনা',
                'icon' => 'pi pi-users',
                'sort_order' => 7
            ],
            [
                'name' => 'Khata & Loose',
                'name_bn' => 'খাতা ও লুজ',
                'slug' => 'khata_loose',
                'description' => 'Khata and loose management',
                'description_bn' => 'খাতা ও লুজ ব্যবস্থাপনা',
                'icon' => 'pi pi-book',
                'sort_order' => 8
            ],
            [
                'name' => 'Negran Management',
                'name_bn' => 'নেগরান সংক্রান্ত',
                'slug' => 'negran',
                'description' => 'Negran management',
                'description_bn' => 'নেগরান ব্যবস্থাপনা',
                'icon' => 'pi pi-eye',
                'sort_order' => 9
            ],
            [
                'name' => 'Mumtahin Management',
                'name_bn' => 'মুমতাহিন সংক্রান্ত',
                'slug' => 'mumtahin',
                'description' => 'Mumtahin management',
                'description_bn' => 'মুমতাহিন ব্যবস্থাপনা',
                'icon' => 'pi pi-check-circle',
                'sort_order' => 10
            ],
            [
                'name' => 'Zone Management',
                'name_bn' => 'জোন সংক্রান্ত',
                'slug' => 'zone',
                'description' => 'Zone management',
                'description_bn' => 'জোন ব্যবস্থাপনা',
                'icon' => 'pi pi-map',
                'sort_order' => 11
            ],
            [
                'name' => 'Attendance',
                'name_bn' => 'উপস্থিতি',
                'slug' => 'attendance',
                'description' => 'Attendance management',
                'description_bn' => 'উপস্থিতি ব্যবস্থাপনা',
                'icon' => 'pi pi-clock',
                'sort_order' => 12
            ],
            [
                'name' => 'Result Management',
                'name_bn' => 'ফলাফল সংক্রান্ত',
                'slug' => 'result',
                'description' => 'Result management',
                'description_bn' => 'ফলাফল ব্যবস্থাপনা',
                'icon' => 'pi pi-chart-bar',
                'sort_order' => 13
            ],
            [
                'name' => 'Communication',
                'name_bn' => 'যোগাযোগ',
                'slug' => 'communication',
                'description' => 'Messaging and notice management',
                'description_bn' => 'বার্তা এবং নোটিশ ব্যবস্থাপনা',
                'icon' => 'pi pi-comment',
                'sort_order' => 14
            ],
        ];

        foreach ($modules as $module) {
            PermissionModule::firstOrCreate(['slug' => $module['slug']], $module);
        }

        // Get module IDs dynamically
        $setupModule = PermissionModule::where('slug', 'setup')->first();
        $billModule = PermissionModule::where('slug', 'bill')->first();
        $madrasaModule = PermissionModule::where('slug', 'madrasa')->first();
        $markazModule = PermissionModule::where('slug', 'markaz')->first();
        $registrationModule = PermissionModule::where('slug', 'registration')->first();
        $inclusionModule = PermissionModule::where('slug', 'inclusion')->first();
        $resultModule = PermissionModule::where('slug', 'result')->first();

        // Create permissions for each module
        $permissions = [
            // Setup permissions
            ['name' => 'setup.access', 'guard_name' => 'web', 'module_id' => $setupModule->id],
            ['name' => 'setup.marhala', 'guard_name' => 'web', 'module_id' => $setupModule->id],
            ['name' => 'setup.subject', 'guard_name' => 'web', 'module_id' => $setupModule->id],
            ['name' => 'setup.central_exam', 'guard_name' => 'web', 'module_id' => $setupModule->id],
            ['name' => 'setup.user', 'guard_name' => 'web', 'module_id' => $setupModule->id],
            ['name' => 'setup.instruction', 'guard_name' => 'web', 'module_id' => $setupModule->id],

            // Bill permissions
            ['name' => 'bill.access', 'guard_name' => 'web', 'module_id' => $billModule->id],
            ['name' => 'bill.negran', 'guard_name' => 'web', 'module_id' => $billModule->id],
            ['name' => 'bill.mumtahin', 'guard_name' => 'web', 'module_id' => $billModule->id],
            ['name' => 'bill.markaz_admin', 'guard_name' => 'web', 'module_id' => $billModule->id],
            ['name' => 'bill.zonal', 'guard_name' => 'web', 'module_id' => $billModule->id],

            // Madrasa permissions
            ['name' => 'madrasa.access', 'guard_name' => 'web', 'module_id' => $madrasaModule->id],
            ['name' => 'madrasa.list', 'guard_name' => 'web', 'module_id' => $madrasaModule->id],
            ['name' => 'madrasa.misc', 'guard_name' => 'web', 'module_id' => $madrasaModule->id],
            ['name' => 'madrasa.admin', 'guard_name' => 'web', 'module_id' => $madrasaModule->id],
            ['name' => 'madrasa.draft_soft_delete', 'guard_name' => 'web', 'module_id' => $madrasaModule->id],
            ['name' => 'madrasa.payment', 'guard_name' => 'web', 'module_id' => $madrasaModule->id],

            // Markaz permissions
            ['name' => 'markaz.access', 'guard_name' => 'web', 'module_id' => $markazModule->id],
            ['name' => 'markaz.application_list', 'guard_name' => 'web', 'module_id' => $markazModule->id],
            ['name' => 'markaz.proposed', 'guard_name' => 'web', 'module_id' => $markazModule->id],
            ['name' => 'markaz.list', 'guard_name' => 'web', 'module_id' => $markazModule->id],
            ['name' => 'markaz.change_application', 'guard_name' => 'web', 'module_id' => $markazModule->id],
            ['name' => 'markaz.admin', 'guard_name' => 'web', 'module_id' => $markazModule->id],
            ['name' => 'markaz.admin_training', 'guard_name' => 'web', 'module_id' => $markazModule->id],
            ['name' => 'markaz.group', 'guard_name' => 'web', 'module_id' => $markazModule->id],

            // Registration permissions
            ['name' => 'registration.access', 'guard_name' => 'web', 'module_id' => $registrationModule->id],
            ['name' => 'registration.list', 'guard_name' => 'web', 'module_id' => $registrationModule->id],
            ['name' => 'registration.payment', 'guard_name' => 'web', 'module_id' => $registrationModule->id],
            ['name' => 'registration.card_create', 'guard_name' => 'web', 'module_id' => $registrationModule->id],

            // Inclusion permissions
            ['name' => 'inclusion.access', 'guard_name' => 'web', 'module_id' => $inclusionModule->id],
            ['name' => 'inclusion.list', 'guard_name' => 'web', 'module_id' => $inclusionModule->id],
            ['name' => 'inclusion.roll_generate', 'guard_name' => 'web', 'module_id' => $inclusionModule->id],
            ['name' => 'inclusion.admit_card', 'guard_name' => 'web', 'module_id' => $inclusionModule->id],

            // Result permissions
            ['name' => 'result.access', 'guard_name' => 'web', 'module_id' => $resultModule->id],
            ['name' => 'result.condition', 'guard_name' => 'web', 'module_id' => $resultModule->id],
            ['name' => 'result.correction', 'guard_name' => 'web', 'module_id' => $resultModule->id],
            ['name' => 'result.review', 'guard_name' => 'web', 'module_id' => $resultModule->id],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Create default roles
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web'
        ]);

        $assistantSuperAdmin = Role::firstOrCreate([
            'name' => 'assistant-super-admin',
            'guard_name' => 'web'
        ]);

        $boardAdmin = Role::firstOrCreate([
            'name' => 'board-admin',
            'guard_name' => 'web'
        ]);

        // Give all permissions to super admin
        $superAdmin->givePermissionTo(Permission::all());

        // Give limited permissions to assistant super admin
        $assistantSuperAdmin->givePermissionTo([
            'setup.access',
            'madrasa.access',
            'markaz.access',
            'registration.access',
            'inclusion.access'
        ]);

        // Give basic permissions to board admin
        $boardAdmin->givePermissionTo([
            'madrasa.list',
            'registration.list',
            'result.access'
        ]);
    }
}
