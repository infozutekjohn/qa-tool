<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ApiTestRunner
{
    public function runAndGenerate(array $params): array
    {
        $projectRoot = base_path();
        $projectCode = now()->format('Ymd-His-u');
        $resultsDir  = $projectRoot . '/allure-results/' . $projectCode;

        $flags = $params['flags'] ?? ($params['testGroups'] ?? []);
        if (!is_array($flags)) $flags = [];

        // REQUIRED
        $flags['login']  = true;
        $flags['logout'] = true;

        $selectedGroups = array_keys(array_filter($flags, fn($v) => (bool)$v));

        Log::info('ApiTestRunner - This is the selectedGroups', ['data' => $selectedGroups]);

        $categoryMap = [
            'login'     => $projectRoot . '/tests/Feature/LoginTest.php',
            'casino'    => $projectRoot . '/tests/Feature/CasinoTest.php',
            'live'      => $projectRoot . '/tests/Feature/LiveTest.php',
            'bonus'     => $projectRoot . '/tests/Feature/BonusTest.php',
            'error'     => $projectRoot . '/tests/Feature/ErrorTest.php',
            'gameslink' => $projectRoot . '/tests/Feature/FeatureTest.php',
            'logout'    => $projectRoot . '/tests/Feature/LogoutTest.php',
        ];

        $selectedFiles = [];
        foreach ($selectedGroups as $g) {
            if (isset($categoryMap[$g])) $selectedFiles[] = $categoryMap[$g];
        }
        $selectedFiles = array_values(array_unique($selectedFiles));

        if (empty($selectedFiles)) {
            throw new \RuntimeException('No test files selected.');
        }

        foreach ($selectedFiles as $f) {
            if (!File::exists($f)) {
                throw new \RuntimeException("Selected test file does not exist: {$f}");
            }
        }

        File::makeDirectory($resultsDir, 0775, true, true);

        $tempDir = getenv('TEMP') ?: getenv('TMP') ?: sys_get_temp_dir();

        $env = array_merge($_ENV, $_SERVER, [
            'APP_ENV'       => 'testing',
            'TEST_USERNAME' => (string)($params['username'] ?? ''),
            'TEST_TOKEN'    => (string)($params['token'] ?? ''),
            'TEST_ENDPOINT' => (string)($params['endpoint'] ?? ''),

            'TEST_CASINO_GAME_CODE' => (string)($params['casinoGameCode'] ?? ''),
            'TEST_LIVE_GAME_CODE'   => (string)($params['liveGameCode'] ?? ''),
            'TEST_CROSS_GAME_CODE'  => (string)($params['crossGameCode'] ?? ''),
            'TEST_LAUNCH_ALIAS'     => (string)($params['launchAlias'] ?? ''),

            'TEST_BET_PRIMARY'      => (string)($params['betPrimary'] ?? ''),
            'TEST_BET_SECONDARY'    => (string)($params['betSecondary'] ?? ($params['betPrimary'] ?? '')),
            'TEST_WIN_PRIMARY'      => (string)($params['winPrimary'] ?? ''),
            'TEST_WIN_AMOUNT'       => (string)($params['winPrimary'] ?? ''),

            'ALLURE_RESULTS_DIRECTORY' => $resultsDir,
            // keep fallback for safety (since your config checks it too)
            'ALLURE_OUTPUT_DIR' => $resultsDir,

            'TEMP' => $tempDir,
            'TMP'  => $tempDir,
            'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
        ]);

        $cmd = [
            'php',
            'vendor/bin/phpunit',
            '--configuration',
            $projectRoot . '/phpunit.xml',
            ...$selectedFiles,
        ];

        Log::info('ApiTestRunner - ApiTestRunner: PHPUnit command', [
            'project' => $projectCode,
            'files'   => $selectedFiles,
            'allure'  => $resultsDir,
            'cmd'     => $cmd,
        ]);

        Log::info('ApiTestRunner - ApiTestRunner: ALLURE env check', [
            'ALLURE_RESULTS_DIRECTORY' => $env['ALLURE_RESULTS_DIRECTORY'] ?? null,
            'ALLURE_OUTPUT_DIR'        => $env['ALLURE_OUTPUT_DIR'] ?? null,
            'phpunit_xml_has_fallback' => true,
        ]);

        $process = new Process($cmd, $projectRoot, $env);
        $process->setTimeout(600);
        $process->run();

        $exitCode = (int)($process->getExitCode() ?? 1);

        // check if results exist
        $resultJson = glob($resultsDir . '/*-result.json') ?: [];
        Log::info('ApiTestRunner - ApiTestRunner: Allure dir check', [
            'dir' => $resultsDir,
            'result_json_count' => count($resultJson),
        ]);

        // If no allure results, RETURN but don't throw (so DB row updates and UI can display output)
        // if (count($resultJson) === 0) {
        //     return [
        //         'exitCode'    => $exitCode,
        //         'projectCode' => $projectCode,
        //         'reportUrl'   => null,
        //         'output'      => $process->getOutput(),
        //         'error'       => $process->getErrorOutput(),
        //     ];
        // }

        $files = glob($resultsDir . '/*') ?: [];
        $files = array_values(array_filter($files, fn($p) => is_file($p)));

        Log::info('ApiTestRunner - ApiTestRunner: about to send results to allure docker', [
            'project' => $projectCode,
            'dir' => $resultsDir,
            'total_files' => count($files),
            'attachments' => count(glob($resultsDir . '/*-attachment') ?: []),
            'results' => count(glob($resultsDir . '/*-result.json') ?: []),
        ]);

        $attachmentFiles = glob($resultsDir . '/*-attachment') ?: [];
        $attachmentSizes = array_map(fn($p) => [basename($p), filesize($p)], $attachmentFiles);

        Log::info('ApiTestRunner - attachment file sizes (local)', [
            'project' => $projectCode,
            'count' => count($attachmentFiles),
            'sample' => array_slice($attachmentSizes, 0, 10),
        ]);

        // Generate report via allure-docker-service
        $reportUrl = $this->generateDockerReport($resultsDir, $projectCode);

        return [
            'exitCode'    => $exitCode,
            'projectCode' => $projectCode,
            'reportUrl'   => $reportUrl,
            'output'      => $process->getOutput(),
            'error'       => $process->getErrorOutput(),
        ];
    }

    private function generateDockerReport(string $resultsDir, string $projectCode): string
    {
        $internalBase = rtrim((string)config('services.allure.internal_base_url', 'http://allure:5050'), '/');
        $publicBase   = rtrim((string)config('services.allure.public_base_url', 'http://localhost:5050'), '/');

        $files = glob($resultsDir . '/*') ?: [];
        $files = array_values(array_filter($files, fn($p) => is_file($p)));

        $attachmentFiles = array_values(array_filter($files, fn($p) => str_ends_with($p, '-attachment')));
        Log::info('Allure local files', [
            'project' => $projectCode,
            'dir' => $resultsDir,
            'total' => count($files),
            'attachments' => count($attachmentFiles),
            'attachment_sizes_sample' => array_slice(array_map(fn($p) => [basename($p), filesize($p)], $attachmentFiles), 0, 10),
        ]);

        $client = new Client([
            'base_uri'        => $internalBase,
            'timeout'         => 300,
            'connect_timeout' => 10,
            'http_errors'     => false,
        ]);

        $chunkSize = 25;
        $chunks = array_chunk($files, $chunkSize);

        foreach ($chunks as $i => $chunk) {
            $payload = ['results' => []];

            foreach ($chunk as $path) {
                $content = File::get($path);

                if ($content === '' || $content === null) {
                    continue;
                }

                $payload['results'][] = [
                    'file_name'      => basename($path),
                    'content_base64' => base64_encode($content),
                ];
            }

            Log::info('Allure send-results chunk', [
                'project' => $projectCode,
                'chunk' => ($i + 1) . '/' . count($chunks),
                'count' => count($payload['results']),
            ]);

            $resp = $client->post('/allure-docker-service/send-results', [
                'query' => [
                    'project_id'             => $projectCode,
                    'force_project_creation' => 'true',
                ],
                'json' => $payload,
            ]);

            $status = $resp->getStatusCode();
            $body   = (string)$resp->getBody();

            Log::info('Allure send-results response', [
                'project' => $projectCode,
                'chunk'   => ($i + 1),
                'status'  => $status,
                'body'    => mb_substr($body, 0, 1500),
            ]);

            if ($status < 200 || $status >= 300) {
                throw new \RuntimeException("Allure send-results failed (HTTP {$status})");
            }
        }

        // generate report
        $resp = $client->get('/allure-docker-service/generate-report', [
            'query' => ['project_id' => $projectCode],
        ]);

        $status = $resp->getStatusCode();
        $rawBody = (string)$resp->getBody();

        Log::info('Allure generate-report response', [
            'project' => $projectCode,
            'status'  => $status,
            'body'    => mb_substr($rawBody, 0, 1500),
        ]);

        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException("Allure generate-report failed (HTTP {$status})");
        }

        $body = json_decode($rawBody, true);

        $raw = $body['data']['report_url']
            ?? "/allure-docker-service/projects/{$projectCode}/reports/latest/index.html";

        if (is_string($raw) && preg_match('#^https?://#i', $raw)) {
            return str_replace($internalBase, $publicBase, $raw);
        }

        return $publicBase . '/' . ltrim($raw, '/');
    }
}
