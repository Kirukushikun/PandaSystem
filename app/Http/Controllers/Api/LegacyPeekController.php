<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\RequestorModel;
use Illuminate\Http\JsonResponse;

class LegacyPeekController extends Controller
{
    public function employee(string $companyId): JsonResponse
    {
        $employee = Employee::where('company_id', $companyId)->first();

        if (!$employee) {
            return response()->json([
                'found' => false,
                'checked_at' => now()->toIso8601String(),
            ]);
        }

        $pans = RequestorModel::with('preparer')
            ->where('employee_id', $companyId)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $latest = $pans->first();

        return response()->json([
            'found' => true,
            'employee' => [
                'company_id' => $employee->company_id,
                'name' => $employee->full_name,
                'department' => $employee->department,
                'position' => $employee->position,
                'farm' => $employee->farm,
            ],
            'latest_pan' => $latest ? $this->panSnapshot($latest) : null,
            'recent_pans' => $pans->map(fn (RequestorModel $pan) => [
                'pan_number' => $pan->request_no,
                'status' => $pan->request_status,
                'type_of_action' => $pan->type_of_action,
                'created_at' => $pan->created_at->toIso8601String(),
            ])->values(),
            'checked_at' => now()->toIso8601String(),
        ]);
    }

    private function panSnapshot(RequestorModel $pan): array
    {
        $preparer = $pan->preparer;

        return [
            'pan_number' => $pan->request_no,
            'status' => $pan->request_status,
            'type_of_action' => $pan->type_of_action,
            'department_snapshot' => $pan->department,
            'employment_status' => $preparer->employment_status ?? null,
            'justification' => $pan->justification,
            'action_reference_data' => $preparer->action_reference_data ?? null,
            // No per-status timestamp exists on `requestor`; submitted_at is the closest
            // real signal, falling back to updated_at for rows that never went through
            // the requestor submit flow (e.g. seeded/imported data).
            'filed_at' => optional($pan->submitted_at ?? $pan->updated_at)->toIso8601String(),
        ];
    }
}
