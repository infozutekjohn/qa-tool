<?php

namespace Tests\Support;

use Qameta\Allure\Allure;

trait ApiResponseValidator
{
    protected function validateApiResponse(
        array $context
    ): void {
        /**
         * Expected keys in $context:
         *
         * required:
         * - response
         * - data
         * - payload
         * - checks
         * - fullUrl
         * - body
         *
         * optional:
         * - endpointType (getbalance|bet|result|transferfunds)
         * - includeBalance (bool)
         * - errorScenario (bool)
         * - balanceAction (added|deducted|unchanged|error|null)
         * - win
         * - bet
         */

        $response        = $context['response'];
        $data            = $context['data'];
        $payload         = $context['payload'];
        $checks          = $context['checks'];
        $fullUrl         = $context['fullUrl'];
        $body            = $context['body'];

        $endpointType    = $context['endpointType']    ?? null;
        $includeBalance  = $context['includeBalance']  ?? false;
        $errorScenario   = $context['errorScenario']   ?? false;
        $balanceAction   = $context['balanceAction']   ?? null;

        // Attach HTTP artifacts for checking the request/response payload
        $this->attachHttpRequestAndResponse(
            $fullUrl,
            $payload,
            $response,
            $body ?? null
        );

        $this->stepAssertStatus($response, 200, $checks);

        // Triggered on error scenario only
        if (!$errorScenario) {
            $this->stepAssertNoErrorField($data);
        }

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTimestampFormat(
            $data,
            $checks,
            [
                'includeBalance' => $includeBalance,
                'ignoreDefault'  => true,
            ]
        );

        $this->stepAssertTimestampGMT(
            $data,
            $checks,
            [
                'includeBalance' => $includeBalance,
                'ignoreDefault'  => true,
            ]
        );

        // Schema / type validation
        // Endpoint type determines what type of schema to check
        if ($endpointType) {
            $this->stepAssertTransactionResponseSchema(
                $data,
                $checks,
                ['type' => $endpointType]
            );
        }

        // Balance validations
        switch ($balanceAction) {
            case 'added':
                $this->stepAssertBalanceWinAdded(
                    $data,
                    $context['win'] ?? null,
                    'Win amount',
                    $checks
                );
                break;

            case 'deducted':
                $this->stepAssertBalanceDeducted(
                    $data,
                    $context['bet'] ?? null,
                    $checks
                );
                break;

            case 'unchanged':
                $this->stepAssertBalanceUnchanged(
                    $data,
                    'Retry - balance unchanged',
                    $checks
                );
                break;
        }

        $this->stepAssertBalanceError($data, $checks);

        // getbalance-only tracking
        if ($endpointType === 'getbalance') {
            $this->updateTrackedBalance($data);
        }
    }
}
