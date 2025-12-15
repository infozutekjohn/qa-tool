<?php

namespace Tests\Config;

final class Endpoint
{
    private const DEFAULTS = [
        'playtech' => [
            'authenticate'      => '/to-operator/playtech/authenticate',
            'getbalance'        => '/to-operator/playtech/getbalance',
            'bet'               => '/to-operator/playtech/bet',
            'gameroundresult'   => '/to-operator/playtech/gameroundresult',
            'transferfunds'     => '/to-operator/playtech/transferfunds',
            'notifybonusevent'  => '/to-operator/playtech/notifybonusevent',
            'livetip'           => '/to-operator/playtech/livetip',
            'logout'            => '/to-operator/playtech/logout',
        ],
    ];

    public static function playtech(string $name): string
    {
        // Allow override via env (phpunit.xml / .env)
        $envKey = 'PT_' . strtoupper($name) . '_ENDPOINT';
        $fromEnv = getenv($envKey);

        if ($fromEnv !== false && $fromEnv !== '') {
            return $fromEnv;
        }

        // Fallback to hardcoded default in ONE place
        if (!isset(self::DEFAULTS['playtech'][$name])) {
            throw new \InvalidArgumentException("Unknown Playtech endpoint '$name'");
        }

        return self::DEFAULTS['playtech'][$name];
    }
}
