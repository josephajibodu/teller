<?php

namespace JosephAjibodu\Teller\Gateways;

use Exception;
use InvalidArgumentException;
use JosephAjibodu\Teller\Helpers\TellerConfig;

class GatewayFactory
{
    /**
     * @throws Exception
     */
    public static function make(string $name)
    {
        $config = TellerConfig::get("gateways.{$name}");

        return match ($name) {
            'paystack' => new PaystackGateway($config['secret_key']),
            'flutterwave' => new FlutterwaveGateway($config['secret_key']),
            default => throw new InvalidArgumentException("Unsupported gateway: {$name}"),
        };
    }
}
