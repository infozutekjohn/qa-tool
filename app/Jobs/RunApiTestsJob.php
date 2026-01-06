<?php

namespace App\Jobs;

use App\Models\TestRun;
use App\Services\ApiTestRunner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunApiTestsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $testRunId,
        public array $params
    ) {}

    public function handle(ApiTestRunner $runner): void
    {
        /**
         * ============================================================
         * HARD LOCK — guarantees this run executes ONLY ONCE
         * ============================================================
         */
        $lock = cache()->lock("run-api-tests:{$this->testRunId}", 600);

        if (!$lock->get()) {
            Log::warning('RunApiTestsJob - RunApiTestsJob blocked duplicate execution', [
                'run_id' => $this->testRunId,
            ]);
            return;
        }

        Log::info('RunApiTestsJob - RunApiTestsJob STARTED', [
            'run_id' => $this->testRunId,
            'time'   => now()->toDateTimeString(),
        ]);

        $run = null;

        try {
            $run = TestRun::find($this->testRunId);

            if (!$run) {
                Log::error('RunApiTestsJob - RunApiTestsJob: TestRun not found', [
                    'run_id' => $this->testRunId,
                ]);
                return;
            }


            // MARK AS RUNNING
            $run->update([
                'status'        => 'running',
                'error_message' => null,
                'started_at'    => now(),
            ]);

            // EXECUTE TESTS
            $result = $runner->runAndGenerate($this->params);

            // FINAL UPDATE

            $run->update([
                'phpunit_exit' => $result['exitCode'],
                'project_code' => $result['projectCode'],
                'report_url'   => $result['reportUrl'],
                'status'       => 'success',
                'finished_at'  => now(),
            ]);

            // $run->update([
            //     'phpunit_exit' => $result['exitCode'],
            //     'project_code' => $result['projectCode'],
            //     'report_url'   => $result['reportUrl'],
            //     'status'       => ($result['exitCode'] === 0) ? 'success' : 'failed',
            //     'finished_at'  => now(),
            // ]);

            Log::info('RunApiTestsJob - RunApiTestsJob FINISHED', [
                'run_id'        => $this->testRunId,
                'exit_code'     => $result['exitCode'],
                'project_code'  => $result['projectCode'],
            ]);
        } catch (\Throwable $e) {
            if ($run) {
                $run->update([
                    'status'        => 'failed',
                    'error_message' => $e->getMessage(),
                    'finished_at'   => now(),
                ]);
            }

            Log::error('RunApiTestsJob - RunApiTestsJob FAILED', [
                'run_id' => $this->testRunId,
                'error'  => $e->getMessage(),
                'trace'  => $e->getTraceAsString(),
            ]);
        } finally {
            optional($lock)->release();
        }
    }
}
