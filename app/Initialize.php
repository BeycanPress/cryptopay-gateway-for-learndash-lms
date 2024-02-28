<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPay\LearnDash;

use BeycanPress\CryptoPay\Integrator\Helpers;

class Initialize
{
    /**
     * Constructor
     */
    public function __construct()
    {
        Helpers::registerIntegration('learndash');
        Helpers::createTransactionPage(
            esc_html__('LearnDash transactions', 'cryptopay'),
            'learndash',
        );

        add_filter('learndash_payment_gateways', [$this, 'registerGateway']);
        add_action('learndash_settings_sections_init', [$this, 'registerSection']);
    }

    /**
     * Register gateway
     *
     * @param array<\Learndash_Payment_Gateway> $gateways
     * @return array<\Learndash_Payment_Gateway>
     */
    public function registerGateway(array $gateways): array
    {
        return array_merge($gateways, [
            new Gateways\GatewayLite()
        ]);
    }

    /**
     * @return void
     */
    public function registerSection(): void
    {
        Sections\SectionLite::add_section_instance();
    }
}
