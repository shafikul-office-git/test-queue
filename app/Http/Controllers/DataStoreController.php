<?php

namespace App\Http\Controllers;

use App\Models\DataStore;
use Illuminate\Http\Request;
use App\Imports\ExcelDataImport;
use App\Jobs\ImportExcelDataJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DataStoreController extends Controller
{
    public function fileDataStore()
    {
        $files = Storage::disk('public')->files('uploads');
        $fullPaths = [];

        foreach ($files as $file) {
            $fullPaths[] = storage_path('app/public/' . $file);
            Log::info('test', ['test' => $files]);
        }

        return "ok";
    }

    public function importAllExcelFiles()
    {
        try {
            $files = Storage::disk('public')->files('uploads');

            $excelFiles = array_filter($files, function ($file) {
                return in_array(pathinfo($file, PATHINFO_EXTENSION), ['xlsx', 'xls']);
            });

            foreach ($excelFiles as $file) {
                $filePath = storage_path('app/public/' . $file);
                ImportExcelDataJob::dispatch($filePath);
            }

            return 'Process started for all files';
        } catch (\Exception $e) {
            Log::error('Error uploading Excel files: ' . $e->getMessage());
            return 'Process Error';
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DataStore $dataStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataStore $dataStore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataStore $dataStore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataStore $dataStore)
    {
        //
    }
}
