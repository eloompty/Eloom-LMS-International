<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLocationTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import locaton table from an SQL file';

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
     * @return int
     */
    public function handle()
    {
        $sqlPath = public_path('locations/locations.sql');

        if (File::exists($sqlPath)) {
            DB::unprepared(File::get($sqlPath));
            $this->info('Locations Table imported successfully!');
        } else {
            $this->error('SQL file not found.');
        }
    }
}
