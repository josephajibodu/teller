<?php

namespace JosephAjibodu\Teller\Helpers;

class TellerConfig
{
    protected static array $config = [
        'default_gateway' => 'paystack',
        'gateways' => [
            'paystack' => ['secret' => ''],
            'flutterwave' => ['secret' => ''],
        ],
        'proration' => [
            'enabled' => true,
            'rounding' => 'up',
        ],
    ];

    public static function set(array $config): void
    {
        static::$config = array_replace_recursive(static::$config, $config);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        // Try Laravel config first if available
        if (function_exists('config') && class_exists('Illuminate\Support\Facades\Config')) {
            try {
                $laravelValue = \config("teller.{$key}");
                if ($laravelValue !== null) {
                    return $laravelValue;
                }
            } catch (\Exception $e) {
                // Laravel not available, continue with static config
            }
        }

        // Fallback to static config
        $segments = explode('.', $key);
        $value = static::$config;

        foreach ($segments as $segment) {
            if (!isset($value[$segment])) return $default;
            $value = $value[$segment];
        }

        return $value;
    }
}
