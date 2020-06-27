<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ModuleRequestGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:request-crud {module} {--prefix=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate crud requests';

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
        $module = $this->argument('module');
        $prefix = $this->option('prefix');

        $cruds = ['Create', 'Update', 'Delete', 'Restore', 'Get'];

        foreach ($cruds as $crud) {
            $name = "$module/$crud{$module}Request";

            if ($prefix) {
                $name =  "$prefix/$name";
            }

            $this->call("make:request", compact('name'));
        }
    }
}
