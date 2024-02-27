<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPay\Integrator;

use BeycanPress\CryptoPay\Loader;
use BeycanPress\CryptoPay\Helpers as ProHelpers;
use BeycanPress\CryptoPayLite\Loader as LiteLoader;
use BeycanPress\CryptoPayLite\Helpers as LiteHelpers;

class Helpers
{
    /**
     * @return bool
     */
    public static function bothExists(): bool
    {
        return static::exists() || static::liteExists();
    }

    /**
     * @return bool
     */
    public static function liteExists(): bool
    {
        return class_exists(LiteLoader::class);
    }

    /**
     * @return bool
     */
    public static function exists(): bool
    {
        return class_exists(Loader::class);
    }

    /**
     * @param string $addon
     * @return void
     */
    public static function registerIntegration(string $addon): void
    {
        if (self::exists()) {
            ProHelpers::registerIntegration($addon);
        }

        if (self::liteExists()) {
            LiteHelpers::registerIntegration($addon);
        }
    }

    /**
     * @param string $method
     * @param array<mixed> $args
     * @return mixed
     */
    public static function run(string $method, array $args = []): mixed
    {
        if (self::exists()) {
            return ProHelpers::$method(...$args);
        } else {
            return LiteHelpers::$method(...$args);
        }
    }
}
