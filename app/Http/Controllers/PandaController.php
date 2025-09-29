<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\PandaExport;
use App\Imports\PandaImport;
use Maatwebsite\Excel\Facades\Excel;

class PandaController extends Controller
{
    public function export()
    {
        return Excel::download(new PandaExport(), 'PANDA System - Employees.xlsx');
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new PandaImport;
        Excel::import($import, $request->file('file'));

        session()->flash('notif', [
            'type' => 'success',
            'header' => 'Import Successful',
            'message' => "Import finished: {$import->createdCount} new rows, {$import->updatedCount} updated."
        ]);

        return back();
    }
}
