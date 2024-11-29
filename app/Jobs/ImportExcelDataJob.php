<?php

namespace App\Jobs;

use App\Imports\ExcelDataImport;
use App\Models\DataStore;
use App\Models\ExcelData;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportExcelDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filePath;
    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing Excel file: ' . $this->filePath);

        Excel::import(new ExcelDataImport, $this->filePath);

        Log::info('Excel file processing completed: ' . $this->filePath);
    }
}
