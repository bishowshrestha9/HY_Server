<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSwaggerDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'l5-swagger:generate {--force : Force generation}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate Swagger documentation (uses static JSON file)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Swagger documentation uses static JSON file at: storage/api-docs/api-docs.json');
        $this->info('No automatic generation is performed due to library constraints.');
        $this->info('To update API docs, manually edit: storage/api-docs/api-docs.json');
        
        return 0;
    }
}
