<?php

namespace App\Http\Controllers;

use App\Models\TestRun;
use App\Services\ApiTestRunner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestRunController extends Controller
{
    public function store(Request $request, ApiTestRunner $runner)
    {
        // Increase execution time limit for long-running PHPUnit tests
        set_time_limit(300); // 5 minutes

        // $data = $request->validate([
        //     'username' => 'required|string',
        //     'token'    => 'required|string',
        // ]);

        $data = $request->validate([
            'username' => 'required|string',
            'token'    => 'required|string',
            'endpoint' => 'nullable|string',
            'testGroups' => 'nullable|array',

            // game codes
            'casinoGameCode' => 'nullable|string',
            'liveGameCode'   => 'nullable|string',
            'crossGameCode'  => 'nullable|string',
            'launchAlias'    => 'nullable|string',

            // bets & wins
            'betPrimary'   => 'nullable',
            'betSecondary' => 'nullable',
            'winPrimary'   => 'nullable',

            // primary bonus
            'remoteBonusCodePrimary'   => 'nullable|string',
            'bonusInstanceCodePrimary' => 'nullable|string',
            'bonusTemplatePrimary'     => 'nullable|string',

            // secondary bonus
            'remoteBonusCodeSecondary'   => 'nullable|string',
            'bonusInstanceCodeSecondary' => 'nullable|string',
            'bonusTemplateSecondary'     => 'nullable|string',

            // jackpot selector
            'jackpot' => 'nullable|string',

            // live table details
            'tableId'   => 'nullable|string',
            'tableName' => 'nullable|string',

            // jackpot IDs
            'jackpotIdMain' => 'nullable|string',
            'jackpotId110'  => 'nullable|string',
            'jackpotId120'  => 'nullable|string',
            'jackpotId130'  => 'nullable|string',
            'jackpotId140'  => 'nullable|string',
        ]);

        Log::info('Data has been generated', ['data' => $data]);

        // Debug: Log raw testGroups from request
        Log::info('TestGroups from request', [
            'testGroups_raw'       => $request->input('testGroups'),
            'testGroups_validated' => $data['testGroups'] ?? 'NOT IN DATA',
        ]);

        // $testRun = $runner->run($data['username'], $data['token'], $request->input('endpoint'));

        $testRun = $runner->run($data);

        Log::info('Test Run Result', ['test_data' => [$testRun]]);

        return response()->json([
            'id'           => $testRun->id,
            'username'     => $testRun->username,
            'phpunit_exit' => $testRun->phpunit_exit,
            'project_id'   => $testRun->project_code,
            'report_url'   => $testRun->report_url,
            'created_at'   => $testRun->created_at,
        ]);
    }


    public function index()
    {
        return TestRun::orderByDesc('id')->limit(50)->get();
    }
}
