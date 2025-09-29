<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class PandaImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{   
    public $createdCount = 0;
    public $updatedCount = 0;

    public function model(array $row)
    {
        // Check if record already exists
        $existing = Employee::where('company_id', $row['employee_id'])
            ->where('full_name', $row['employee_full_name'])
            ->where('position', $row['position'])
            ->first();

        if ($existing) {
            return null; // skip duplicates
        }

        // Create new record
        $model = Employee::create([
            'company_id'     => $row['employee_id'],
            'full_name'      => $row['employee_full_name'],
            'position'       => $row['position'],
            'farm'           => $row['farm'],
            'department'     => $row['department'],
        ]);

        $this->createdCount++;

        return $model;
    }


}
