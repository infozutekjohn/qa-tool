<?php

namespace App\Services;

use App\Models\TestRun;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ApiTestRunner
{
    public function run(array $params): TestRun
    {
        $projectRoot = base_path();
        $resultsDir  = $projectRoot . '/allure-results';
        $reportDir   = $projectRoot . '/public/allure-reports';

        $username = $params['username'];
        $token    = $params['token'];
        $endpoint = $params['endpoint'] ?? null;

        // Selected PHPUnit groups from UI
        $selectedGroups = array_keys(
            array_filter($params['testGroups'] ?? [])
        );

        // DEBUG: Log the testGroups and selected groups
        Log::info('ApiTestRunner: Test group selection', [
            'testGroups_received' => $params['testGroups'] ?? 'NOT SET',
            'selectedGroups'      => $selectedGroups,
        ]);

        // 1. Prepare env for PHPUnit (explicitly)
        // Include TEMP/TMP and DNS-related settings to avoid Windows cURL issues
        $tempDir = getenv('TEMP') ?: getenv('TMP') ?: sys_get_temp_dir();

        // Get form values with defaults
        $casinoGameCode = $params['casinoGameCode'] ?? '';
        $liveGameCode   = $params['liveGameCode'] ?? '';
        $crossGameCode  = $params['crossGameCode'] ?? '';
        $launchAlias    = $params['launchAlias'] ?? '';
        $betPrimary     = $params['betPrimary'] ?? '1';
        $winPrimary     = $params['winPrimary'] ?? '2';

        // Live table details
        $tableId   = $params['tableId'] ?? '1234';
        $tableName = $params['tableName'] ?? 'Integration Test';

        // Jackpot IDs
        $jackpotIdMain = $params['jackpotIdMain'] ?? '';
        $jackpotId110  = $params['jackpotId110'] ?? '';
        $jackpotId120  = $params['jackpotId120'] ?? '';
        $jackpotId130  = $params['jackpotId130'] ?? '';
        $jackpotId140  = $params['jackpotId140'] ?? '';

        // DEBUG: Log all environment variables being set
        Log::info('ApiTestRunner: Setting environment variables for PHPUnit', [
            'TEST_USERNAME'  => $username,
            'TEST_TOKEN'     => substr($token, 0, 20) . '...',  // Truncate for security
            'TEST_ENDPOINT'  => $endpoint,
            'TEST_CASINO_GAME_CODE' => $casinoGameCode,
            'TEST_LIVE_GAME_CODE'   => $liveGameCode,
            'TEST_CROSS_GAME_CODE'  => $crossGameCode,
            'TEST_LAUNCH_ALIAS'     => $launchAlias,
            'TEST_BET_PRIMARY'      => $betPrimary,
            'TEST_WIN_PRIMARY'      => $winPrimary,
            'TEST_TABLE_ID'         => $tableId,
            'TEST_TABLE_NAME'       => $tableName,
        ]);

        $env = array_merge($_ENV, $_SERVER, [
            'APP_ENV'        => 'testing',
            'TEST_USERNAME'  => $username,
            'TEST_TOKEN'     => $token,
            'TEST_ENDPOINT'  => $endpoint,

            // game codes
            'TEST_CASINO_GAME_CODE' => $casinoGameCode,
            'TEST_LIVE_GAME_CODE'   => $liveGameCode,
            'TEST_CROSS_GAME_CODE'  => $crossGameCode,
            'TEST_LAUNCH_ALIAS'     => $launchAlias,

            // RNG game code (use casino game code as fallback)
            'TEST_RNG_GAME_CODE' => $casinoGameCode,

            // Crosslaunch games (S60 Feature Tests) - reuse live game code and launch alias
            'TEST_CROSSLAUNCH_GAME_1'  => $liveGameCode,
            'TEST_CROSSLAUNCH_ALIAS_1' => $launchAlias,
            'TEST_CROSSLAUNCH_GAME_2'  => $casinoGameCode,

            // GameCodeName check (S60) - use casino game code
            'TEST_GAMECODENAME_GAME' => $casinoGameCode,

            // bets & wins
            'TEST_BET_PRIMARY'   => $betPrimary,
            'TEST_BET_SECONDARY' => $params['betSecondary'] ?? $betPrimary,
            'TEST_WIN_PRIMARY'   => $winPrimary,
            'TEST_WIN_AMOUNT'    => $winPrimary,

            // Transfer and jackpot amounts (derived from win amount)
            'TEST_TRANSFER_AMOUNT'     => $winPrimary,
            'TEST_JACKPOT_WIN_AMOUNT'  => $winPrimary,
            'TEST_BONUS_BALANCE_CHANGE' => $winPrimary,

            // primary bonus
            'TEST_REMOTE_BONUS_CODE_PRIMARY'   => $params['remoteBonusCodePrimary'] ?? '',
            'TEST_BONUS_INSTANCE_CODE_PRIMARY' => $params['bonusInstanceCodePrimary'] ?? '',
            'TEST_BONUS_TEMPLATE_PRIMARY'      => $params['bonusTemplatePrimary'] ?? '',

            // secondary bonus
            'TEST_REMOTE_BONUS_CODE_SECONDARY'   => $params['remoteBonusCodeSecondary'] ?? '',
            'TEST_BONUS_INSTANCE_CODE_SECONDARY' => $params['bonusInstanceCodeSecondary'] ?? '',
            'TEST_BONUS_TEMPLATE_SECONDARY'      => $params['bonusTemplateSecondary'] ?? '',

            // jackpot option
            'TEST_JACKPOT' => $params['jackpot'] ?? '',

            // Live table details
            'TEST_TABLE_ID'   => $tableId,
            'TEST_TABLE_NAME' => $tableName,

            // Jackpot IDs for casino/live tests
            'TEST_JACKPOT_ID_MAIN' => $jackpotIdMain,
            'TEST_JACKPOT_ID_110'  => $jackpotId110,
            'TEST_JACKPOT_ID_120'  => $jackpotId120,
            'TEST_JACKPOT_ID_130'  => $jackpotId130,
            'TEST_JACKPOT_ID_140'  => $jackpotId140,

            // This is what Allure will use if you rely on ALLURE_OUTPUT_DIR
            'ALLURE_OUTPUT_DIR' => $resultsDir,

            // Windows environment variables to help with DNS/temp issues
            'TEMP' => $tempDir,
            'TMP' => $tempDir,
            'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
        ]);

        // DEBUG: Log a subset of the final env array to verify values
        Log::info('ApiTestRunner: Final environment array (TEST_* vars)', [
            'env_TEST_USERNAME' => $env['TEST_USERNAME'] ?? 'NOT SET',
            'env_TEST_TOKEN'    => isset($env['TEST_TOKEN']) ? substr($env['TEST_TOKEN'], 0, 20) . '...' : 'NOT SET',
            'env_TEST_ENDPOINT' => $env['TEST_ENDPOINT'] ?? 'NOT SET',
            'env_TEST_CASINO_GAME_CODE' => $env['TEST_CASINO_GAME_CODE'] ?? 'NOT SET',
        ]);

        // 2. Ensure allure-results exists + clean it
        if (! File::exists($resultsDir)) {
            File::makeDirectory($resultsDir, 0775, true);
        } else {
            File::cleanDirectory($resultsDir);
        }

        // 3. Run PHPUnit from project root
        $cmd = [
            'php',
            'vendor/bin/phpunit',
            '--configuration',
            $projectRoot . '/phpunit.xml',
        ];

        if (!empty($selectedGroups)) {
            $cmd[] = '--group';
            $cmd[] = implode(',', $selectedGroups);
        }

        // DEBUG: Log the final PHPUnit command
        Log::info('ApiTestRunner: PHPUnit command', [
            'command' => implode(' ', $cmd),
        ]);

        $process = new Process($cmd, $projectRoot, $env);
        $process->setTimeout(600); // 10 minutes
        $process->run();

        $exitCode = $process->getExitCode();

        // If PHPUnit itself failed, stop here and show why
        if (! $process->isSuccessful()) {
            Log::warning('PHPUnit had failing tests in ApiTestRunner', [
                'command'  => implode(' ', $cmd),
                'exitCode' => $exitCode,
                'output'   => $process->getOutput(),
                'error'    => $process->getErrorOutput(),
            ]);
        }

        // 4. Collect Allure result files
        $files = File::exists($resultsDir) ? File::files($resultsDir) : [];

        if (empty($files)) {
            throw new \RuntimeException(
                "No Allure result files found after PHPUnit run. " .
                    "Exit code: {$exitCode}\n" .
                    $process->getOutput() . "\n" .
                    $process->getErrorOutput()
            );
        }

        $projectCode = now()->format('Ymd-His');

        // 5. Try Docker Allure Service first, fallback to local CLI
        $useLocalAllure = config('services.allure.use_local', false);

        if (!$useLocalAllure) {
            try {
                $fullReportUrl = $this->generateDockerReport($files, $projectCode);
            } catch (ConnectException $e) {
                Log::info('Docker Allure not available, falling back to local CLI');
                $fullReportUrl = $this->generateLocalReport($projectRoot, $resultsDir, $reportDir, $projectCode);
            }
        } else {
            $fullReportUrl = $this->generateLocalReport($projectRoot, $resultsDir, $reportDir, $projectCode);
        }

        // 6. Save to DB
        return TestRun::create([
            'username'      => $username,
            'phpunit_exit'  => $exitCode,
            'project_code'  => $projectCode,
            'report_url'    => $fullReportUrl,
        ]);
    }

    /**
     * Generate report using Docker Allure Service
     */
    private function generateDockerReport(array $files, string $projectCode): string
    {
        $payload = ['results' => []];

        foreach ($files as $file) {
            $content = File::get($file->getRealPath());
            if ($content === '' || $content === null) {
                continue;
            }
            $payload['results'][] = [
                'file_name'      => $file->getFilename(),
                'content_base64' => base64_encode($content),
            ];
        }

        if (empty($payload['results'])) {
            throw new \RuntimeException('Allure result files were empty.');
        }

        $internalBase = rtrim(config('services.allure.internal_base_url'), '/');
        $publicBase   = rtrim(config('services.allure.public_base_url'), '/');

        $client = new Client([
            'base_uri' => $internalBase,
            'timeout'  => 60,
            'connect_timeout' => 5,
        ]);

        // send-results
        $client->post('/allure-docker-service/send-results', [
            'query' => [
                'project_id'             => $projectCode,
                'force_project_creation' => 'true',
            ],
            'json' => $payload,
        ]);

        // generate-report
        $resp = $client->get('/allure-docker-service/generate-report', [
            'query' => ['project_id' => $projectCode],
        ]);

        $body = json_decode((string) $resp->getBody(), true);
        $reportUrlFromAllure = $body['data']['report_url'] ?? null;

        if ($reportUrlFromAllure) {
            if (str_starts_with($reportUrlFromAllure, 'http')) {
                $path = parse_url($reportUrlFromAllure, PHP_URL_PATH) ?? '';
                $query = parse_url($reportUrlFromAllure, PHP_URL_QUERY);
                $fragment = parse_url($reportUrlFromAllure, PHP_URL_FRAGMENT);

                $fullReportUrl = $publicBase . $path;

                if ($query) {
                    $fullReportUrl .= '?' . $query;
                }
                if ($fragment) {
                    $fullReportUrl .= '#' . $fragment;
                }
            } else {
                $fullReportUrl = $publicBase . '/' . ltrim($reportUrlFromAllure, '/');
            }
        } else {
            $fullReportUrl = $publicBase . "/allure-docker-service/projects/{$projectCode}/reports/latest/index.html";
        }

        return $fullReportUrl;
    }

    /**
     * Generate report using local Allure CLI
     */
    private function generateLocalReport(string $projectRoot, string $resultsDir, string $reportDir, string $projectCode): string
    {
        $projectReportDir = $reportDir . '/' . $projectCode;

        // Ensure report directory exists
        if (! File::exists($reportDir)) {
            File::makeDirectory($reportDir, 0775, true);
        }

        // Find allure CLI - use the direct .bat file on Windows (doesn't require node in PATH)
        if (PHP_OS_FAMILY === 'Windows') {
            $allureBin = $projectRoot . '/node_modules/allure-commandline/dist/bin/allure.bat';
        } else {
            $allureBin = $projectRoot . '/node_modules/allure-commandline/dist/bin/allure';
        }

        if (! File::exists($allureBin)) {
            // Fallback to global allure
            $allureBin = PHP_OS_FAMILY === 'Windows' ? 'allure.bat' : 'allure';
        }

        // Generate report
        $cmd = [$allureBin, 'generate', $resultsDir, '-o', $projectReportDir, '--clean'];

        // Set JAVA_HOME and TEMP for the Allure process (PHP built-in server may not have them)
        $env = null;
        if (PHP_OS_FAMILY === 'Windows') {
            $javaHome = getenv('JAVA_HOME') ?: 'C:\\Program Files\\Microsoft\\jdk-17.0.17.10-hotspot';
            $tempDir = getenv('TEMP') ?: getenv('TMP') ?: sys_get_temp_dir();
            $env = array_merge($_ENV, $_SERVER, [
                'JAVA_HOME' => $javaHome,
                'PATH' => getenv('PATH') . ';' . $javaHome . '\\bin',
                'TEMP' => $tempDir,
                'TMP' => $tempDir,
            ]);
        }

        $process = new Process($cmd, $projectRoot, $env);
        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::error('Local Allure report generation failed', [
                'output' => $process->getOutput(),
                'error'  => $process->getErrorOutput(),
            ]);
            throw new \RuntimeException('Failed to generate local Allure report: ' . $process->getErrorOutput());
        }

        Log::info('Local Allure report generated', ['path' => $projectReportDir]);

        // Return URL to the local report (served from public folder)
        return '/allure-reports/' . $projectCode . '/index.html';
    }
}
