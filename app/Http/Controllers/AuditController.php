<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use Auth;
use DataTables;
use App\Models\User;

class AuditController extends Controller
{
    public static function logEntry($data)
    {
    	$log = new Audit();
    	$log->action = $data[0];
    	$log->table = $data[1];
    	$log->old_value = $data[2];
    	$log->new_value = $data[3];
    	$log->user_id = Auth::user()->id;
    	$log->save();
    }


    public function trail(Request $request)
    {
        if($request->ajax()) {
            $audits = Audit::all();
 
            $data = collect();
            if($audits->count() > 0) {
                foreach($audits as $a) {
                    $data->push([
                        'id' => $a->id,
                        'user' => $a->user_id,
                        'action' => strtoupper($a->action),
                        'table' => strtoupper($a->table),
                        'new_value' => $a->new_value,
                        'old_value' => $a->old_value,
                        'view' => '<button class="btn btn-success btn-sm">VIEW</button>'
                    ]);
                }
            }
            return DataTables::of($data)
                    ->rawColumns(['view'])
                    ->make(true);
        }
        return view('audits');
    }
}
