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
        // Determine what to save in department column
        $departmentValue = $this->determineDepartment($row['division'] ?? '', $row['department'] ?? '');

        // Check if record already exists
        $existing = Employee::where('company_id', $row['employee_id'])
            ->where('full_name', $row['employee_full_name'])
            ->where('position', $row['position'])
            ->first();

        if ($existing) {
            // Update the existing record
            $existing->update([
                'farm'       => $row['farm'],
                'department' => $departmentValue,
            ]);
            
            $this->updatedCount++;
            return null;
        }

        // Create new record if doesn't exist
        $model = Employee::create([
            'company_id'     => $row['employee_id'],
            'full_name'      => $row['employee_full_name'],
            'position'       => $row['position'],
            'farm'           => $row['farm'],
            'department'     => $departmentValue,
        ]);

        $this->createdCount++;

        return $model;
    }

    private function determineDepartment($division, $department)
    {
        // Clean up the values
        $division = trim($division);
        $department = trim($department);

        // If division is "Shared Services", use the department value
        if (strcasecmp($division, 'Shared Services') === 0) {
            return $department;
        }

        // Otherwise, use the division value
        return $division;
    }

}
