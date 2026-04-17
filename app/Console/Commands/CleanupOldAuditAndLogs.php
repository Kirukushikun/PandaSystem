<?php

namespace App\Console\Commands;

use App\Models\Audit;
use App\Models\LogModel;
use Illuminate\Console\Command;

class CleanupOldAuditAndLogs extends Command
{
    protected $signature = 'cleanup:audit-logs {--days=90 : Delete records older than this many days}';

    protected $description = 'Delete old audit and correction log records.';

    public function handle()
    {
        $days = (int) $this->option('days');

        if ($days < 1) {
            $this->error('The --days option must be at least 1.');
            return self::FAILURE;
        }

        $cutoff = now()->subDays($days);

        $deletedAudits = Audit::where('created_at', '<', $cutoff)->delete();
        $deletedLogs = LogModel::where('created_at', '<', $cutoff)->delete();

        $this->info("Deleted {$deletedAudits} audit record(s) and {$deletedLogs} correction log record(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
