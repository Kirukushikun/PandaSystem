<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LogModel;
use App\Models\RequestorModel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * One-time export for the PANDA v2 migration. Runs here — not in v2 — because
 * decryption needs THIS app's APP_KEY; every encrypted field is read through
 * the model's own getAttribute() override so it comes out already plaintext.
 * Output is plain JSON, carrying no v1 encryption baggage, ready for a v2-side
 * importer to map onto its own schema (statuses, the 9→11 department split, etc.)
 * — this command makes no mapping decisions itself, only extracts and decrypts.
 */
class ExportForV2 extends Command
{
    protected $signature = 'panda:export-for-v2 {--path=legacy-export : Directory under storage/app to write into}';

    protected $description = 'Decrypt and export PANDA v1 data as plain JSON for the v2 migration';

    public function handle(): int
    {
        $dir = storage_path('app/'.$this->option('path'));
        File::ensureDirectoryExists($dir);

        $this->exportDepartments($dir);
        $this->exportEmployees($dir);
        $this->exportUsers($dir);
        $this->exportRequests($dir);
        $this->printMappingSummary($dir);

        $this->info("Done. Files written to: {$dir}");

        return self::SUCCESS;
    }

    private function exportDepartments(string $dir): void
    {
        $rows = Department::orderBy('id')->get(['id', 'name'])->toArray();
        File::put($dir.'/departments.json', json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->line('departments.json — '.count($rows).' rows');
    }

    private function exportEmployees(string $dir): void
    {
        $rows = Employee::orderBy('id')->get()->map(fn (Employee $e) => [
            'legacy_id' => $e->id,
            'company_id' => $e->company_id, // matches v2's employees.employee_no
            'full_name' => $e->full_name,
            'farm' => $e->farm,
            'department' => $e->department,
            'position' => $e->position,
            'has_ongoing' => (bool) $e->hasOngoing,
        ])->all();
        File::put($dir.'/employees.json', json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->line('employees.json — '.count($rows).' rows');
    }

    private function exportUsers(string $dir): void
    {
        // No email in v1 — accounts were never tied to the external auth API here.
        // v2 grants access through the User Directory (real API ids), so this is
        // reference-only: who existed, what they could do, for cross-checking names.
        $rows = User::orderBy('id')->get()->map(fn (User $u) => [
            'legacy_id' => $u->id,
            'name' => $u->name,
            'position' => $u->position,
            'farm' => $u->farm,
            'role' => $u->role,
            'access' => $u->access,
            'is_confidentiality_approver' => (bool) $u->is_confidentiality_approver,
        ])->all();
        File::put($dir.'/users.json', json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->line('users.json — '.count($rows).' rows');
    }

    private function exportRequests(string $dir): void
    {
        // requestor.employee_id is NOT employees.id (v1's internal PK) — it's already
        // employees.company_id directly. Verified: employees.id tops out at 502, but
        // requestor.employee_id ranges 6-1651, matching company_id's range. A pluck('company_id',
        // 'id') lookup here silently returns null for ~82% of rows (701/853), which the v2-side
        // importer surfaced as almost every PAN failing employee resolution.
        $validCompanyIds = Employee::pluck('company_id')->flip();
        $logsByRequest = LogModel::orderBy('id')->get()->groupBy('request_id');

        $rows = RequestorModel::with('preparer')->orderBy('id')->get()
            ->map(function (RequestorModel $r) use ($validCompanyIds, $logsByRequest) {
                $p = $r->preparer;

                return [
                    'legacy_id' => $r->id,
                    'legacy_reference' => $r->request_no,
                    'confidentiality' => $r->confidentiality,      // plaintext, e.g. 'tarlac'
                    'farm' => $r->farm,
                    'employee_id' => $r->employee_id,
                    'employee_company_id' => $validCompanyIds->has($r->employee_id) ? $r->employee_id : null, // join key for v2
                    'employee_name' => $r->employee_name,
                    'department' => $r->department,                // needs v2-side mapping — see summary
                    'type_of_action' => $r->type_of_action,
                    'justification' => $r->justification,           // decrypted
                    'supporting_file_url' => $r->supporting_file_url,
                    'supporting_file_name' => $r->supporting_file_name,
                    'request_status' => $r->request_status,         // needs v2-side mapping — see summary
                    'current_handler' => $r->current_handler,
                    'is_deleted' => (bool) $r->is_deleted,
                    'is_deleted_by' => $r->is_deleted_by,
                    'requested_by' => $r->requested_by,             // decrypted
                    'requestor_id' => $r->requestor_id,             // decrypted
                    'divisionhead_id' => $r->divisionhead_id,       // decrypted
                    'hr_id' => $r->hr_id,                           // decrypted
                    'approver_id' => $r->approver_id,               // plaintext in v1
                    'submitted_at' => $r->submitted_at?->toIso8601String(),
                    'created_at' => $r->created_at?->toIso8601String(),
                    'updated_at' => $r->updated_at?->toIso8601String(),
                    'preparer' => $p ? [
                        'date_hired' => optional($p->date_hired)->toIso8601String(),
                        'employment_status' => $p->employment_status,
                        'division' => $p->division,
                        'doe_from' => optional($p->doe_from)->toIso8601String(),
                        'doe_to' => optional($p->doe_to)->toIso8601String(),
                        'wage_no' => $p->wage_no,
                        'action_reference_data' => $p->action_reference_data, // decrypted, JSON-decoded if it was JSON
                        'remarks' => $p->remarks,
                        'has_allowances' => (bool) $p->has_allowances,
                        'prepared_by' => $p->prepared_by,
                        'approved_by' => $p->approved_by,
                    ] : null,
                    'returns' => $logsByRequest->get($r->id, collect())->map(fn (LogModel $l) => [
                        'origin' => $l->origin,
                        'header' => $l->header,
                        'body' => $l->body,
                        'created_at' => $l->created_at?->toIso8601String(),
                    ])->values()->all(),
                ];
            })->all();

        File::put($dir.'/requests.json', json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->line('requests.json — '.count($rows).' rows');
    }

    /**
     * The two things a v2-side importer MUST decide before running: how each
     * distinct department name maps onto v2's 11, and how each distinct
     * (request_status, current_handler) pair maps onto PanStatus. Printed as
     * a starter mapping table so the decision is made once, deliberately —
     * not guessed per-row inside the importer.
     */
    private function printMappingSummary(string $dir): void
    {
        $departments = RequestorModel::pluck('department')->unique()->filter()->sort()->values();
        // request_status alone is the state machine — no application logic ever reads
        // current_handler. But current_handler is NOT noise for the migration: because
        // PanrecordsTable::initiatePan() never sets it, the surviving 'requestor' default
        // marks HR-initiated PANs and 'division head' marks requestor-submitted ones — a
        // 100%-faithful origin discriminator, agreeing with `requested_by IS NULL` on all
        // 853 rows. It's emitted per-row above; see MAPPING_NEEDED.md "Origin / initiated-by".
        $statuses = RequestorModel::query()
            ->selectRaw('request_status, count(*) as c')
            ->groupBy('request_status')
            ->orderByDesc('c')
            ->get();
        $actionTypes = RequestorModel::query()
            ->selectRaw('type_of_action, count(*) as c')
            ->groupBy('type_of_action')
            ->orderByDesc('c')
            ->get();

        File::put($dir.'/MAPPING_NEEDED.md', "# v1 → v2 mapping to decide before importing\n\n"
            ."## Departments seen on requests (map each to one of v2's 11)\n\n"
            .$departments->map(fn ($d) => "- [ ] {$d} → ")->implode("\n")
            ."\n\n## request_status values seen (map each to a PanStatus)\n\n"
            .$statuses->map(fn ($row) => "- [ ] \"{$row->request_status}\" ({$row->c} rows) → ")->implode("\n")
            ."\n\n## Action types seen on requests (map each to a v2 ActionType)\n\n"
            .$actionTypes->map(fn ($row) => "- [ ] \"{$row->type_of_action}\" ({$row->c} rows) → ")->implode("\n")
            ."\n");

        $this->line('MAPPING_NEEDED.md — fill this in before writing the v2 importer');
    }
}