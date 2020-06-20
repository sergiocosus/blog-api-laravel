<?php

namespace App\Console\Commands;

use App\Core\Security\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportTypescriptPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helpers:front-end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate angular typescript helpers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissionList = Permission::all()->pluck('name');

        $translateKeys = $permissionList
            ->map(function($permission) {return "_('permissions.$permission');";})
            ->reduce(function($carry, $item){return  "$carry$item\n";}, '');

        Storage::delete('permission-translations.ts');
        Storage::prepend('permission-translations.ts',
            "import { _ } from '@cobra/shared/_';\n\n".$translateKeys);


        $enum = $permissionList
            ->map(function($permission) {return camel_case($permission)." = '$permission',";})
            ->sort()
            ->reduce(function($carry, $item){return  "$carry  $item\n";}, '');

        Storage::delete('permission-enum.ts');
        Storage::prepend('permission-enum.ts', "export enum PermissionEnum {\n" . $enum. "}\n");

    }
}
