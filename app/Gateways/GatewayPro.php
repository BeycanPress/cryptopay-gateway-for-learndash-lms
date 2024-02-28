<?php

declare(strict_types=1);

// @phpcs:disable Generic.Files.LineLength
// @phpcs:disable Generic.Files.InlineHTML
// @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace BeycanPress\CryptoPay\LearnDash\Gateways;

use LearnDash\Core\Models\Product;
use BeycanPress\CryptoPay\Payment;
use BeycanPress\CryptoPay\Helpers;
use BeycanPress\CryptoPay\PluginHero\Hook;
use BeycanPress\CryptoPay\LearnDash\Sections\SectionPro;

class GatewayPro extends AbstractGateway
{
    protected static string $name = 'cryptopay';

    protected static string $title = 'CryptoPay';

    protected static string $ldPath = 'ld_cryptopay';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(SectionPro::class);
        Hook::addFilter('before_payment_finished_learndash', [$this, 'cpPaymentFinished']);
        Hook::addFilter('payment_redirect_urls_learndash', [$this, 'cpPaymentRedirectUrls']);
    }

    /**
     * @param Product $product
     * @return void
     */
    public function start(Product $product): void
    {
        if (!$this->is_ready()) {
            return;
        }

        Hook::addFilter('theme', function () {
            return $this->settings['theme'] ?? 'light';
        });

        Helpers::addStyle('main.min.css');
        $cp = (new Payment('learndash'))->modal();

        add_action('wp_footer', function () use ($cp): void {
            echo $cp;
        });
    }

    /**
     * @return array<string>
     */
    public function get_deps(): array
    {
        return [Helpers::getProp('mainJsKey', '')];
    }

    /**
     * @return bool
     */
    protected function is_test_mode(): bool
    {
        return Helpers::getTestnetStatus();
    }
}
