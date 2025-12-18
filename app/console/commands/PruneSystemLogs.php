<?php

namespace App\Console\Commands;

use App\Models\SystemLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PruneSystemLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * --days : optional override for days to retain
     * --batch : optional batch size for deletes to avoid big transactions
     */
    protected $signature = 'dblog:prune 
                            {--days= : Number of days to retain (overrides config)} 
                            {--batch=1000 : Batch size for deletion (default 1000)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune system_logs older than retention days (safe batched deletes)';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?: Config::get('dblog.retention_days', 30));
        $batch = (int) $this->option('batch') ?: 1000;

        $this->info("Pruning system_logs older than {$days} day(s). Batch size: {$batch}");

        $cutoff = now()->subDays($days)->toDateTimeString();

        // Use DB delete in batches by id to avoid large single transaction
        $idsQuery = DB::table('system_logs')
            ->select('id')
            ->where('created_at', '<', $cutoff)
            ->orderBy('id');

        $total = $idsQuery->count();

        if ($total === 0) {
            $this->info('No logs to prune.');
            return 0;
        }

        $this->info("Found {$total} log(s) to delete. Deleting in batches of {$batch}...");

        $deleted = 0;

        // chunk by id to avoid loading too many ids into memory
        $idsQuery->chunkById($batch, function ($rows) use (&$deleted, $batch) {
            $ids = collect($rows)->pluck('id')->all();
            if (!empty($ids)) {
                $count = DB::table('system_logs')->whereIn('id', $ids)->delete();
                $deleted += $count;
                $this->info("Deleted batch of {$count} rows (cumulative: {$deleted}).");
            }
        });

        $this->info("Prune complete. Total deleted: {$deleted}.");

        return 0;
    }
}
