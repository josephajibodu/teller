<?php

namespace JosephAjibodu\Teller;

use JosephAjibodu\Teller\Services\GatewayManager;

class Teller
{
    protected static ?GatewayManager $manager = null;

    public static function make(): GatewayManager
    {
        return static::$manager ??= new GatewayManager();
    }

    public static function gateway(string $name): GatewayManager
    {
        return (new GatewayManager())->useGateway($name);
    }

    public static function for($user): GatewayManager
    {
        return static::make()->forUser($user);
    }

    // Allow static shortcuts like Teller::subscriptions()->create()
    public static function __callStatic($method, $args)
    {
        return static::make()->{$method}(...$args);
    }
}
