<?php

declare(strict_types=1);

// @phpcs:disable Generic.Files.LineLength
// @phpcs:disable Generic.Files.InlineHTML
// @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace BeycanPress\CryptoPay\LearnDash\Gateways;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPay\LearnDash\Sections\SectionLite;

class GatewayLite extends AbstractGateway
{
    protected static string $name = 'cryptopay_lite';

    protected static string $title = 'CryptoPay Lite';

    protected static string $ldPath = 'ld_cryptopay_lite';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(SectionLite::class);
    }

    /**
     * @return bool
     */
    protected function is_test_mode(): bool
    {
        return Helpers::getTestnetStatus();
    }
}
