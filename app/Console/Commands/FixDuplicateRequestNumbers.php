<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDuplicateRequestNumbers extends Command
{
    protected $signature = 'pan:fix-duplicate-request-nos {--dry-run : Preview changes without updating the database}';

    protected $description = 'Rename duplicate PAN request numbers by appending alphabetical suffixes.';

    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');

        $usedRequestNos = DB::table('requestor')
            ->whereNotNull('request_no')
            ->pluck('request_no')
            ->flip()
            ->all();

        $duplicates = DB::table('requestor')
            ->select('request_no')
            ->whereNotNull('request_no')
            ->groupBy('request_no')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('request_no');

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate request numbers found.');

            return self::SUCCESS;
        }

        $updates = [];

        foreach ($duplicates as $requestNo) {
            $rows = DB::table('requestor')
                ->select('id', 'request_no')
                ->where('request_no', $requestNo)
                ->orderBy('id')
                ->get();

            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    continue;
                }

                $newRequestNo = $this->generateUniqueDuplicateRequestNo($requestNo, $index + 1, $usedRequestNos);

                $usedRequestNos[$newRequestNo] = true;

                $updates[] = [
                    'id' => $row->id,
                    'from' => $row->request_no,
                    'to' => $newRequestNo,
                ];
            }
        }

        if (empty($updates)) {
            $this->info('Duplicate groups were found, but no rows required renaming.');

            return self::SUCCESS;
        }

        foreach ($updates as $update) {
            $this->line("#{$update['id']}: {$update['from']} -> {$update['to']}");
        }

        if ($dryRun) {
            $this->warn('Dry run complete. No database changes were made.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($updates) {
            foreach ($updates as $update) {
                DB::table('requestor')
                    ->where('id', $update['id'])
                    ->update(['request_no' => $update['to']]);
            }
        });

        $this->info('Duplicate request numbers fixed successfully.');

        return self::SUCCESS;
    }

    private function generateUniqueDuplicateRequestNo(string $baseRequestNo, int $duplicateIndex, array $usedRequestNos): string
    {
        $suffixIndex = $duplicateIndex;

        do {
            $candidate = $baseRequestNo . '-' . $this->alphabeticalSuffix($suffixIndex);
            $exists = array_key_exists($candidate, $usedRequestNos);

            $suffixIndex++;
        } while ($exists);

        return $candidate;
    }

    private function alphabeticalSuffix(int $index): string
    {
        $value = '';

        while ($index > 0) {
            $index--;
            $value = chr(65 + ($index % 26)) . $value;
            $index = intdiv($index, 26);
        }

        return $value;
    }
}
