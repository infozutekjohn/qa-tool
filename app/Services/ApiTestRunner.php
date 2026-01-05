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

        /**
         * IMPORTANT:
         * Each test run MUST have its own allure-results directory
         */
        $projectCode = now()->format('Ymd-His');
        $resultsDir  = $projectRoot . "/allure-results/{$projectCode}";
        $reportDir   = $projectRoot . '/public/allure-reports';

        $username = $params['username'];
        $token    = $params['token'];
        $endpoint = $params['endpoint'] ?? null;

        // Selected PHPUnit groups from UI
        $selectedGroups = array_keys(
            array_filter($params['flags'] ?? [])
        );

        // Temp directory (Windows-safe)
        $tempDir = getenv('TEMP') ?: getenv('TMP') ?: sys_get_temp_dir();

        // Game codes
        $casinoGameCode = $params['casinoGameCode'] ?? '';
        $liveGameCode   = $params['liveGameCode'] ?? '';
        $crossGameCode  = $params['crossGameCode'] ?? '';
        $launchAlias    = $params['launchAlias'] ?? '';
        $betPrimary     = $params['betPrimary'] ?? '1';
        $winPrimary     = $params['winPrimary'] ?? '2';

        // Live table
        $tableId   = $params['tableId'] ?? '1234';
        $tableName = $params['tableName'] ?? 'Integration Test';

        // Jackpot IDs
        $jackpotIdMain = $params['jackpotIdMain'] ?? '';
        $jackpotId110  = $params['jackpotId110'] ?? '';
        $jackpotId120  = $params['jackpotId120'] ?? '';
        $jackpotId130  = $params['jackpotId130'] ?? '';
        $jackpotId140  = $params['jackpotId140'] ?? '';

        Log::info('ApiTestRunner: Preparing PHPUnit run', [
            'groups' => $selectedGroups,
            'allure_dir' => $resultsDir,
        ]);

        /**
         * PHPUnit environment variables
         */
        $env = array_merge($_ENV, $_SERVER, [
            'APP_ENV'        => 'testing',
            'TEST_USERNAME'  => $username,
            'TEST_TOKEN'     => $token,
            'TEST_ENDPOINT'  => $endpoint,

            'TEST_CASINO_GAME_CODE' => $casinoGameCode,
            'TEST_LIVE_GAME_CODE'   => $liveGameCode,
            'TEST_CROSS_GAME_CODE'  => $crossGameCode,
            'TEST_LAUNCH_ALIAS'     => $launchAlias,
            'TEST_RNG_GAME_CODE'    => $casinoGameCode,

            'TEST_CROSSLAUNCH_GAME_1'  => $liveGameCode,
            'TEST_CROSSLAUNCH_ALIAS_1' => $launchAlias,
            'TEST_CROSSLAUNCH_GAME_2'  => $casinoGameCode,

            'TEST_GAMECODENAME_GAME' => $casinoGameCode,

            'TEST_BET_PRIMARY'   => $betPrimary,
            'TEST_BET_SECONDARY' => $params['betSecondary'] ?? $betPrimary,
            'TEST_WIN_PRIMARY'   => $winPrimary,
            'TEST_WIN_AMOUNT'    => $winPrimary,

            'TEST_TRANSFER_AMOUNT'      => $winPrimary,
            'TEST_JACKPOT_WIN_AMOUNT'   => $winPrimary,
            'TEST_BONUS_BALANCE_CHANGE' => $winPrimary,

            'TEST_REMOTE_BONUS_CODE_PRIMARY'   => $params['remoteBonusCodePrimary'] ?? '',
            'TEST_BONUS_INSTANCE_CODE_PRIMARY' => $params['bonusInstanceCodePrimary'] ?? '',
            'TEST_BONUS_TEMPLATE_PRIMARY'      => $params['bonusTemplatePrimary'] ?? '',

            'TEST_REMOTE_BONUS_CODE_SECONDARY'   => $params['remoteBonusCodeSecondary'] ?? '',
            'TEST_BONUS_INSTANCE_CODE_SECONDARY' => $params['bonusInstanceCodeSecondary'] ?? '',
            'TEST_BONUS_TEMPLATE_SECONDARY'      => $params['bonusTemplateSecondary'] ?? '',

            'TEST_JACKPOT' => $params['jackpot'] ?? '',

            'TEST_TABLE_ID'   => $tableId,
            'TEST_TABLE_NAME' => $tableName,

            'TEST_JACKPOT_ID_MAIN' => $jackpotIdMain,
            'TEST_JACKPOT_ID_110'  => $jackpotId110,
            'TEST_JACKPOT_ID_120'  => $jackpotId120,
            'TEST_JACKPOT_ID_130'  => $jackpotId130,
            'TEST_JACKPOT_ID_140'  => $jackpotId140,

            // 'ALLURE_OUTPUT_DIR' => $resultsDir,
            'ALLURE_RESULTS_DIRECTORY' => $resultsDir,
            'ALLURE_OUTPUT_DIR'  => $resultsDir,
            'ALLURE_RESULTS_PATH' => $resultsDir,

            'TEMP' => $tempDir,
            'TMP'  => $tempDir,
            'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
        ]);

        /**
         * Create fresh allure-results directory
         */
        File::makeDirectory($resultsDir, 0775, true, true);

        /**
         * Build PHPUnit command (FILE-BASED, bypass categories completely)
         *
         * NOTE:
         * This will make PHPUnit only load the selected test files.
         * Non-selected categories will NOT be discovered => NOT shown in Allure.
         */
        $categoryMap = [
            'login'     => $projectRoot . '/tests/Feature/LoginTest.php',
            'casino'    => $projectRoot . '/tests/Feature/CasinoTest.php',
            'live'      => $projectRoot . '/tests/Feature/LiveTest.php',
            'bonus'     => $projectRoot . '/tests/Feature/BonusTest.php',
            'error'     => $projectRoot . '/tests/Feature/ErrorTest.php',
            'gameslink' => $projectRoot . '/tests/Feature/FeatureTest.php',
            'logout'    => $projectRoot . '/tests/Feature/LogoutTest.php'
        ];

        $selectedFiles = [];
        foreach ($selectedGroups as $groupKey) {
            if (isset($categoryMap[$groupKey])) {
                $selectedFiles[] = $categoryMap[$groupKey];
            }
        }

        $selectedFiles = array_values(array_unique($selectedFiles));

        $cmd = [
            'php',
            'vendor/bin/phpunit',
            '--configuration',
            $projectRoot . '/phpunit.xml',
        ];

        // If nothing selected
        if (empty($selectedFiles)) {
            Log::warning('ApiTestRunner: No test files selected', ['groups' => $selectedGroups]);

            return TestRun::create([
                'username'      => $username,
                'phpunit_exit'  => 0,
                'project_code'  => $projectCode,
                'report_url'    => null,
            ]);
        }

        // Append the test files to run
        foreach ($selectedFiles as $file) {
            $cmd[] = $file;
        }

        Log::info('ApiTestRunner: PHPUnit selected files', [
            'groups' => $selectedGroups,
            'files'  => $selectedFiles,
        ]);


        Log::info('ApiTestRunner: PHPUnit command', ['cmd' => $cmd]);

        $process = new Process($cmd, $projectRoot, $env);
        $process->setTimeout(600);
        $process->run();

        $exitCode = $process->getExitCode();

        if (!$process->isSuccessful()) {
            Log::warning('PHPUnit execution finished with issues', [
                'exit_code' => $exitCode,
                'output'    => $process->getOutput(),
                'error'     => $process->getErrorOutput(),
            ]);
        }

        /**
         * Collect Allure result files (ONLY this run)
         */
        $files = File::exists($resultsDir) ? File::files($resultsDir) : [];

        /**
         * IMPORTANT FIX:
         * Do NOT crash when no tests were executed
         */
        if (empty($files)) {
            Log::warning('ApiTestRunner: No Allure results generated (0 tests executed)', [
                'groups'    => $selectedGroups,
                'exit_code' => $exitCode,
            ]);

            return TestRun::create([
                'username'      => $username,
                'phpunit_exit'  => $exitCode,
                'project_code'  => $projectCode,
                'report_url'    => null,
            ]);
        }

        /**
         * Generate Allure report
         */
        $useLocalAllure = config('services.allure.use_local', false);

        if (!$useLocalAllure) {
            try {
                $fullReportUrl = $this->generateDockerReport($files, $projectCode);
            } catch (ConnectException $e) {
                Log::info('Docker Allure not available, using local CLI');
                $fullReportUrl = $this->generateLocalReport(
                    $projectRoot,
                    $resultsDir,
                    $reportDir,
                    $projectCode
                );
            }
        } else {
            $fullReportUrl = $this->generateLocalReport(
                $projectRoot,
                $resultsDir,
                $reportDir,
                $projectCode
            );
        }

        return TestRun::create([
            'username'      => $username,
            'phpunit_exit'  => $exitCode,
            'project_code'  => $projectCode,
            'report_url'    => $fullReportUrl,
        ]);
    }

    /**
     * Docker Allure report
     */
    private function generateDockerReport(array $files, string $projectCode): string
    {
        $payload = ['results' => []];

        foreach ($files as $file) {
            $content = File::get($file->getRealPath());
            if ($content !== '' && $content !== null) {
                $payload['results'][] = [
                    'file_name'      => $file->getFilename(),
                    'content_base64' => base64_encode($content),
                ];
            }
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

        $client->post('/allure-docker-service/send-results', [
            'query' => [
                'project_id'             => $projectCode,
                'force_project_creation' => 'true',
            ],
            'json' => $payload,
        ]);

        $resp = $client->get('/allure-docker-service/generate-report', [
            'query' => ['project_id' => $projectCode],
        ]);

        $body = json_decode((string) $resp->getBody(), true);

        // return $publicBase . (
        //     $body['data']['report_url']
        //     ?? "/allure-docker-service/projects/{$projectCode}/reports/latest/index.html"
        // );

        $reportUrl = $body['data']['report_url']
            ?? "/allure-docker-service/projects/{$projectCode}/reports/latest/index.html";

        // If already absolute, return as-is (or rewrite host if you want)
        if (preg_match('#^https?://#i', $reportUrl)) {
            // Optional: rewrite internal docker hostname to localhost for browser access
            $reportUrl = str_replace('http://allure:5050', $publicBase, $reportUrl);

            return $reportUrl;
        }

        // If relative, prefix with public base
        return $publicBase . '/' . ltrim($reportUrl, '/');
    }

    /**
     * Local Allure CLI report
     */
    private function generateLocalReport(
        string $projectRoot,
        string $resultsDir,
        string $reportDir,
        string $projectCode
    ): string {
        $projectReportDir = $reportDir . '/' . $projectCode;

        File::makeDirectory($reportDir, 0775, true, true);

        $allureBin = PHP_OS_FAMILY === 'Windows'
            ? $projectRoot . '/node_modules/allure-commandline/dist/bin/allure.bat'
            : $projectRoot . '/node_modules/allure-commandline/dist/bin/allure';

        if (!File::exists($allureBin)) {
            $allureBin = PHP_OS_FAMILY === 'Windows' ? 'allure.bat' : 'allure';
        }

        $cmd = [$allureBin, 'generate', $resultsDir, '-o', $projectReportDir, '--clean'];

        $process = new Process($cmd, $projectRoot);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to generate local Allure report');
        }

        return '/allure-reports/' . $projectCode . '/index.html';
    }
}
