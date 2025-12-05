<?php

namespace App\Services;

use App\Models\TestRun;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ApiTestRunner
{
    // public function run(string $username, string $token, string $endpoint): TestRun
    public function run(array $params): TestRun
    {
        $projectRoot = base_path();
        $resultsDir  = $projectRoot . '/allure-results';

        $username = $params['username'];
        $token    = $params['token'];
        $endpoint = $params['endpoint'] ?? null;

        // 1. Prepare env for PHPUnit (explicitly)
        $env = array_merge($_ENV, $_SERVER, [
            'APP_ENV'        => 'testing',
            'TEST_USERNAME'  => $username,
            'TEST_TOKEN'     => $token,
            'TEST_ENDPOINT'  => $endpoint,

            // game codes
            'TEST_CASINO_GAME_CODE' => $params['casinoGameCode'] ?? '',
            'TEST_LIVE_GAME_CODE'   => $params['liveGameCode'] ?? '',
            'TEST_CROSS_GAME_CODE'  => $params['crossGameCode'] ?? '',
            'TEST_LAUNCH_ALIAS'     => $params['launchAlias'] ?? '',

            // bets & wins
            'TEST_BET_PRIMARY'   => $params['betPrimary'] ?? '',
            'TEST_BET_SECONDARY' => $params['betSecondary'] ?? '',
            'TEST_WIN_PRIMARY'   => $params['winPrimary'] ?? '',

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
            
            // This is what Allure will use if you rely on ALLURE_OUTPUT_DIR
            'ALLURE_OUTPUT_DIR' => $resultsDir,
        ]);

        // 2. Ensure allure-results exists + clean it
        if (! File::exists($resultsDir)) {
            File::makeDirectory($resultsDir, 0775, true);
        } else {
            File::cleanDirectory($resultsDir);
        }

        // 3. Run PHPUnit from project root
        $cmd = ['php', 'vendor/bin/phpunit', '--configuration', $projectRoot . '/phpunit.xml'];

        $process = new Process($cmd, $projectRoot, $env);
        $process->setTimeout(180); // 3 minutes
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
            // If we have no results AND phpunit failed, bubble up a real error.
            throw new \RuntimeException(
                "No Allure result files found after PHPUnit run. " .
                    "Exit code: {$exitCode}\n" .
                    $process->getOutput() . "\n" .
                    $process->getErrorOutput()
            );
        }

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

        // 5. Talk to Allure Docker Service
        $projectCode = now()->format('Ymd-His');
        $internalBase = rtrim(config('services.allure.internal_base_url'), '/');
        $publicBase   = rtrim(config('services.allure.public_base_url'), '/');

        $client = new Client([
            'base_uri' => $internalBase,
            'timeout'  => 60,
        ]);

        // send-results
        $client->post('/allure-docker-service/send-results', [
            'query' => [
                'project_id'           => $projectCode,
                'force_project_creation' => 'true',
            ],
            'json' => $payload,
        ]);

        // generate-report
        $resp = $client->get('/allure-docker-service/generate-report', [
            'query' => ['project_id' => $projectCode],
        ]);

        $body = json_decode((string) $resp->getBody(), true);

        $publicBase = rtrim(config('services.allure.public_base_url'), '/');

        $reportUrlFromAllure = $body['data']['report_url'] ?? null;

        if ($reportUrlFromAllure) {
            // If Allure returned an absolute URL, keep only the path and stick your public host in front
            if (str_starts_with($reportUrlFromAllure, 'http')) {
                $path = parse_url($reportUrlFromAllure, PHP_URL_PATH) ?? '';

                // In case Allure also returns query/hash later, you can capture them too
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
                // Relative URL from Allure
                $fullReportUrl = $publicBase . '/' . ltrim($reportUrlFromAllure, '/');
            }
        } else {
            // Fallback if Allure changed response shape or something
            $fullReportUrl = $publicBase . "/allure-docker-service/projects/{$projectCode}/reports/latest/index.html";
        }


        // 6. Save to DB
        return TestRun::create([
            'username'      => $username,
            'phpunit_exit'  => $exitCode,
            'project_code'  => $projectCode,
            'report_url'    => $fullReportUrl,
        ]);
    }
}
