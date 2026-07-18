<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //Get the service name provided in the Artisan command
        $name = Str::studly($this->argument('name'));

        //To prevent accidentally typing "Service"
        if (! str_ends_with($name, 'Service')) {
            $name .= 'Service';
        } 

        // Path ng Service to ha!! engot ka eh
        $path = app_path("Services/{$name}.php");

        // Pag meron, meron na 
        if (File::exists($path)) {
            $this->error("Service already exists!");
            return Command::FAILURE;
        }

        // For service directory
        File::ensureDirectoryExists(app_path('Services'));
        
        // Load the service template (stub).
        $stub = File::get(base_path('stubs/service.stub'));

        // Replace the placeholder class name with the actual service name.
        $stub = str_replace('SampleClass', $name, $stub);

        // Eto na g-generate ko na 
        File::put($path, $stub);

        //Hello, Hello World hehe
        $this->info("Service [{$name}] created successfully.");
        $this->line("Location: {$path}");
    }
}
