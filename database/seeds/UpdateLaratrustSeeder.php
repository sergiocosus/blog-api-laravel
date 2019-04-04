<?php

use App\Core\Security\Permission;
use App\Core\Security\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLaratrustSeeder extends Seeder {
    private $permissionMap;
    private $roleEstructure;

    public function __construct() {
        $this->permissionMap  = collect(config('laratrust_seeder.permissions_map'));
        $this->roleEstructure = config('laratrust_seeder.role_structure');
    }

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run() {
        $this->truncateLaratrustTables();


        foreach ($this->roleEstructure as $roleName => $resourceDefs) {
            $role = $this->getRole(['name' => $roleName]);

            foreach ($resourceDefs as $resource => $permissionDefs) {
                $permissions = explode(',', $permissionDefs);

                foreach ($permissions as $p => $permissionAbbrev) {
                    $permission = $this->getPermission($resource, $permissionAbbrev);

                    if ( ! $role->hasPermission($permission->name)) {
                        $role->attachPermission($permission);
                    } else {
                        $this->command->info(
                            $roleName . ': ' .
                            $p . ' ' .
                            $permission->name .
                            ' already exist'
                        );
                    }
                }
            }
        }
    }

    private function getRole(array $data) {
        $roleName = array_get($data, 'name');
        $this->command->info('Created/Updated Role ' . strtoupper($roleName));

        return Role::updateOrCreate(
            ['name' => $roleName], [
                'display_name' => ucwords(str_replace('_', ' ', $roleName)),
                'description'  => null,
            ]
        );
    }

    private function getPermission($resource, $permissionAbbrev) {
        $permissionName = $this->permissionMap
            ->get($permissionAbbrev) ?: $permissionAbbrev;

        $permission = Permission::firstOrCreate([
            'name'         => $permissionName . '-' . $resource,
            'display_name' => ucfirst($permissionName) . ' ' . ucfirst($resource),
            'description'  => ucfirst($permissionName) . ' ' . ucfirst($resource),
        ]);

        $this->command->info('Created Permission to ' . $permissionName . ' for ' . $resource);

        return $permission;
    }

    /**
     * Truncates all the laratrust tables and the users table
     * @return    void
     */
    public function truncateLaratrustTables() {
        $this->command->info('Truncating permission_role and permission_user tables');

        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
    }
}
