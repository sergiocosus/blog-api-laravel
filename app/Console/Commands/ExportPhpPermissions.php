<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportPhpPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helpers:permission-php';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate php permissions helpers';

    /**
     * @var \Illuminate\Support\Collection
     */
    private $permissionMap;

    /**
     * @var \Illuminate\Config\Repository
     */
    private $roleEstructure;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->permissionMap = collect(config('laratrust_seeder.permissions_map'));
        $this->roleEstructure = config('laratrust_seeder.role_structure');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissionList = collect();
        foreach ($this->roleEstructure as $roleName => $resourceDefs) {

            foreach ($resourceDefs as $resource => $permissionDefs) {
                $permissions = explode(',', $permissionDefs);

                foreach ($permissions as $p => $permissionAbbrev) {
                    $permission = $this->getPermission($resource, $permissionAbbrev);
                    $permissionList->push($permission);
                }
            }
        }
        $enum = $permissionList
            ->map(function ($permission) {
                return '  const '.\Illuminate\Support\Str::camel($permission)." = '$permission';";
            })
            ->unique()->sort()
            ->reduce(function ($carry, $item) {
                return "$carry  $item\n";
            }, '');

        $filePath = 'app/Core/Security/PermissionsConst.php';

        @unlink($filePath);
        $file = fopen($filePath, 'w');
        fwrite($file, "<?php \nnamespace App\Core\Security; \n\nclass PermissionsConst {\n".$enum."\n}\n");
        fclose($file);
    }

    private function getPermission($resource, $permissionAbbrev)
    {
        $permissionName = $this->permissionMap
            ->get($permissionAbbrev) ?: $permissionAbbrev;

        return $permissionName.'-'.$resource;
    }
}
