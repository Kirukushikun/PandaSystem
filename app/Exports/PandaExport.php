<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PandaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::select(
            'company_id',
            'full_name',
            'position',
            'farm',
            'department'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Employee Full Name',
            'Position',
            'Farm',
            'Department'
        ];
    }
}
