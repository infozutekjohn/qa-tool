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
        set_time_limit(300);

        $data = $request->validate([
            'username' => 'required|string',
            'token'    => 'required|string',
            'endpoint' => 'nullable|string',

            'flags' => 'nullable|array',
            'flags.login'     => 'boolean',
            'flags.casino'    => 'boolean',
            'flags.live'      => 'boolean',
            'flags.bonus'     => 'boolean',
            'flags.error'     => 'boolean',
            'flags.gameslink' => 'boolean',
            'flags.logout'    => 'boolean',

            'casinoGameCode' => 'nullable|string',
            'liveGameCode'   => 'nullable|string',
            'crossGameCode'  => 'nullable|string',
            'launchAlias'    => 'nullable|string',

            'betPrimary'   => 'nullable',
            'betSecondary' => 'nullable',
            'winPrimary'   => 'nullable',

            'remoteBonusCodePrimary'   => 'nullable|string',
            'bonusInstanceCodePrimary' => 'nullable|string',
            'bonusTemplatePrimary'     => 'nullable|string',

            'remoteBonusCodeSecondary'   => 'nullable|string',
            'bonusInstanceCodeSecondary' => 'nullable|string',
            'bonusTemplateSecondary'     => 'nullable|string',

            'jackpot' => 'nullable|string',
        ]);


        Log::info('Data has been generated', ['data' => $data]);

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
