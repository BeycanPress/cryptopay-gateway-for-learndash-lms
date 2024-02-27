<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPay\Integrator;

use BeycanPress\CryptoPay\PluginHero\Hook as ProHook;
use BeycanPress\CryptoPayLite\PluginHero\Hook as LiteHook;

class Hook
{
    /**
     * @param string $name
     * @param mixed $callback
     * @param integer $priority
     * @param integer $acceptedArgs
     * @return void
     */
    public static function addAction(string $name, mixed $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        if (Helpers::exists()) {
            ProHook::addAction($name, $callback, $priority, $acceptedArgs);
        }

        if (Helpers::liteExists()) {
            LiteHook::addAction($name, $callback, $priority, $acceptedArgs);
        }
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function callAction(string $name, mixed ...$args): void
    {
        if (Helpers::exists()) {
            ProHook::callAction($name, ...$args);
        }

        if (Helpers::liteExists()) {
            LiteHook::callAction($name, ...$args);
        }
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function removeAction(string $name, mixed ...$args): void
    {
        if (Helpers::exists()) {
            ProHook::removeAction($name, ...$args);
        }

        if (Helpers::liteExists()) {
            LiteHook::removeAction($name, ...$args);
        }
    }

    /**
     * @param string $name
     * @param mixed $callback
     * @param integer $priority
     * @param integer $acceptedArgs
     * @return mixed
     */
    public static function addFilter(string $name, mixed $callback, int $priority = 10, int $acceptedArgs = 1): mixed
    {
        if (Helpers::exists()) {
            return ProHook::addFilter($name, $callback, $priority, $acceptedArgs);
        }

        if (Helpers::liteExists()) {
            return LiteHook::addFilter($name, $callback, $priority, $acceptedArgs);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public static function callFilter(string $name, mixed $value, mixed ...$args): mixed
    {
        if (Helpers::exists()) {
            return ProHook::callFilter($name, $value, ...$args);
        }

        if (Helpers::liteExists()) {
            return LiteHook::callFilter($name, $value, ...$args);
        }
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function removeFilter(string $name, mixed ...$args): void
    {
        if (Helpers::exists()) {
            ProHook::removeFilter($name, ...$args);
        }

        if (Helpers::liteExists()) {
            LiteHook::removeFilter($name, ...$args);
        }
    }
}
