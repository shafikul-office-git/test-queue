<?php

namespace App\Imports;

use App\Models\ExcelData;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelDataImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        Log::info('Processing row', ['row' => $row]);
        if (empty($row[1]) || empty($row[2])) {
            return null; 
        }
        return new ExcelData([
            'column1' => $row[0] ?? 'check',
            'column2' => $row[1] ?? ' 222' ,
            'column3' => $row[2] ?? '333',
        ]);
    }
}
