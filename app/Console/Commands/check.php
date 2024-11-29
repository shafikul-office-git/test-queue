<?php

namespace App\Console\Commands;

use App\Http\Controllers\DataStoreController;
use Illuminate\Console\Command;

class check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $filess = new DataStoreController();
       $data = $filess->importAllExcelFiles();
       return $data;
    }
}
