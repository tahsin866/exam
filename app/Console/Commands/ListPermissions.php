<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class ListPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:list-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissions = Permission::all()->pluck('name')->toArray();
        
        $this->info('Available Permissions:');
        $this->info('===================');
        
        // Group permissions by module
        $grouped = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            $module = $parts[0];
            $grouped[$module][] = $permission;
        }
        
        foreach ($grouped as $module => $modulePermissions) {
            $this->info("\n{$module}:");
            foreach ($modulePermissions as $permission) {
                $this->line("  - {$permission}");
            }
        }
        
        $this->info("\nTotal permissions: " . count($permissions));
    }
}
