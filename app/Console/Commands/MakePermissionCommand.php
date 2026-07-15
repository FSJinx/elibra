<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakePermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:permission {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates permissions.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        $this->line('Generating permission and permission seeder...');
        $this->newLine();

        $this->createPermission($name);
        $this->createSeeder($name);
    }

    protected function createPermission($name = null)
    {
        $stub = File::get(base_path('stubs/permission.stub'));
        $module = Str::of($name)->snake()->replace('_', '.');
        $path = app_path("Permissions/{$name}Permission.php");

        $stub = str_replace(
            ['{{class}}', '{{module}}'],
            ["{$name}Permission", $module],
            $stub
        );

        if (File::exists($path)) {
            $this->components->error("[{$path}] already exists.");
        } else {
            File::put($path, $stub);
            $this->components->info("Permission [{$path}] created successfully.");
        }

    }

    protected function createSeeder($name = null)
    {
        $stub = File::get(base_path('stubs/permission-seeder.stub'));
        $path = database_path("seeders/permissions/{$name}PermissionSeeder.php");

        $stub = str_replace(
            ['{{class}}'],
            ["{$name}Permission"],
            $stub
        );

        if (File::exists($path)) {
            $this->components->error("[{$path}] already exists.");
        } else {
            File::put($path, $stub);
            $this->components->info("Permission [{$path}] created successfully.");
        }
    }

    protected function registerToSeeder($name = null)
    {
        
    }
}
