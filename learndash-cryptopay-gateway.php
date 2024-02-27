<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

// @phpcs:disable PSR1.Files.SideEffects
// @phpcs:disable PSR12.Files.FileHeader
// @phpcs:disable Generic.Files.InlineHTML
// @phpcs:disable Generic.Files.LineLength

/**
 * Plugin Name: LearnDash LMS - CryptoPay Gateway
 * Version:     1.0.0
 * Plugin URI:  https://beycanpress.com/cryptopay/
 * Description: Adds Cryptocurrency payment gateway (CryptoPay) for LearnDash LMS.
 * Author:      BeycanPress LLC
 * Author URI:  https://beycanpress.com
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: ldlms-cryptopay
 * Tags: Cryptopay, Cryptocurrency, WooCommerce, WordPress, MetaMask, Trust, Binance, Wallet, Ethereum, Bitcoin, Binance smart chain, Payment, Plugin, Gateway, Moralis, Converter, API, coin market cap, CMC
 * Requires at least: 5.0
 * Tested up to: 6.4.3
 * Requires PHP: 8.1
*/

// Autoload
require_once __DIR__ . '/vendor/autoload.php';

use BeycanPress\CryptoPay\Integrator\Helpers;

define('LDLMS_CRYPTOPAY_FILE', __FILE__);
define('LDLMS_CRYPTOPAY_VERSION', '1.0.3');
define('LDLMS_CRYPTOPAY_URL', plugin_dir_url(__FILE__));
define('LDLMS_CRYPTOPAY_DIR', plugin_dir_path(__FILE__));

add_action('plugins_loaded', function (): void {

    load_plugin_textdomain('ldlms-cryptopay', false, basename(__DIR__) . '/languages');

    if (!defined('LEARNDASH_VERSION')) {
        add_action('admin_notices', function (): void {
            ?>
                <div class="notice notice-error">
                    <p><?php echo sprintf(esc_html__('LearnDash LMS - CryptoPay Gateway: This plugin requires LearnDash LMS to work. You can buy LearnDash LMS by %s.', 'ldlms-cryptopay'), '<a href="https://www.learndash.com/" target="_blank">' . esc_html__('clicking here', 'ldlms-cryptopay') . '</a>'); ?></p>
                </div>
            <?php
        });
        return;
    }

    if (Helpers::bothExists()) {
        new BeycanPress\CryptoPay\LearnDash\Initialize();
    } else {
        add_action('admin_notices', function (): void {
            ?>
                <div class="notice notice-error">
                    <p><?php echo sprintf(esc_html__('LearnDash LMS - CryptoPay Gateway: This plugin is an extra feature plugin so it cannot do anything on its own. It needs CryptoPay to work. You can buy CryptoPay by %s.', 'ldlms-cryptopay'), '<a href="https://beycanpress.com/product/cryptopay-all-in-one-cryptocurrency-payments-for-wordpress/?utm_source=wp_org_addons&utm_medium=learndash_lms" target="_blank">' . esc_html__('clicking here', 'ldlms-cryptopay') . '</a>'); ?></p>
                </div>
            <?php
        });
    }
});
