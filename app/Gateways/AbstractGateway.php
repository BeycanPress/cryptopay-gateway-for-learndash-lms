<?php

declare(strict_types=1);

// @phpcs:disable Generic.Files.LineLength
// @phpcs:disable Generic.Files.InlineHTML
// @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace BeycanPress\CryptoPay\LearnDash\Gateways;

use LearnDash\Core\Models\Product;
use LearnDash\Core\Models\Transaction;

abstract class AbstractGateway extends \Learndash_Payment_Gateway
{
    /**
     * @var string
     */
    protected static string $name;

    /**
     * @var string
     */
    protected static string $title;

    /**
     * @var string
     */
    protected static string $ldPath;

    /**
     * @var string
     */
    protected string $sectionClass;

    /**
     * @var array<string,mixed>
     */
    // phpcs:ignore
    protected $settings = [];

    /**
     * @var string
     */
    // phpcs:ignore
    protected $currency_code;

    /**
     * @var string
     */
    // phpcs:ignore
    protected $account_id;

    /**
     * @param string $sectionClass
     */
    protected function __construct(string $sectionClass)
    {
        $this->sectionClass = $sectionClass;
        $this->currency_code = mb_strtolower(learndash_get_currency_code());
    }

    /**
     * @return bool
     */
    abstract protected function is_test_mode(): bool;

    /**
     * @return string
     */
    public static function get_name(): string
    {
        return static::$name;
    }

    /**
     * @return string
     */
    public static function get_label(): string
    {
        return static::$title;
    }

    /**
     * @return void
     */
    public function add_extra_hooks(): void
    {
        add_action('wp_footer', array($this, 'show_successful_message'));
    }

    /**
     * @return void
     */
    public function show_successful_message(): void
    {
        if (empty($_GET[static::$ldPath])) {
            return;
        }

        if ('success' !== $_GET[static::$ldPath]) {
            return;
        }

        $message = is_user_logged_in()
            ? __('Your transaction was successful.', 'ldlms-cryptopay')
            : __('Your transaction was successful. Please log in to access your content.', 'ldlms-cryptopay');
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                alert('<?php echo esc_html($message); ?>');
            });
        </script>
        <?php
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->settings = \LearnDash_Settings_Section::get_section_settings_all(
            $this->sectionClass
        );
    }

    /**
     * @return bool
     */
    public function is_ready(): bool
    {
        return 'yes' === ($this->settings['enabled'] ?? '');
    }

    /**
     * It's a ajax action.
     * @return void
     */
    public function setup_payment(): void
    {
        if (empty($_POST['productId'])) {
            return;
        }

        $productId = absint($_POST['productId']);
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));

        if (!isset($_POST['nonce']) && ! wp_verify_nonce($nonce, $this->get_nonce_name())) {
            wp_send_json_error(
                array(
                    'message' => esc_html__('Nonce cannot verified!', 'ldlms-cryptopay'),
                )
            );
        }

        $product = \Product::find($productId);

        if (!$product) {
            wp_send_json_error(
                array(
                    'message' => esc_html__('Product not found.', 'ldlms-cryptopay'),
                )
            );
        }

        wp_send_json_success();
    }

    /**
     * @param Product $product
     * @return void
     */
    private function startCryptoPayHtmlProcess(Product $product): void
    {
        add_action('wp_footer', function () use ($product): void {
            // TODO: Add your html here.
        });
    }

    /**
     * @param array<mixed> $params
     * @param \WP_Post $post
     * @return string
     */
    public function map_payment_button_markup(array $params, \WP_Post $post): string
    {
        if (!is_user_logged_in() && !$this->settings['guestPayment']) {
            return '';
        }

        $product = Product::find(absint($post->ID));
        $this->startCryptoPayHtmlProcess($product);

        $buttonLabel = $this->map_payment_button_label(
            static::$title,
            $post
        );

        $jsonData = [
            'productId' => $product->get_id(),
            'nonce'     => wp_create_nonce($this->get_nonce_name()),
        ];

        $button = '<div class="' . esc_attr($this->get_form_class_name()) . '"><button class="' . esc_attr(\Learndash_Payment_Button::map_button_class_name()) . ' ldlms-cp-btn" type="button" data-name="' . esc_attr(static::$name) . '" data-json="\'' . esc_attr(json_encode($jsonData)) . '\'">' . esc_attr($buttonLabel) . '</button></div>';

        return $button;
    }

    /**
     * @param mixed $entity
     * @param Product $product
     * @return string
     */
    protected function map_transaction_meta(mixed $entity, Product $product): \Learndash_Transaction_Meta_DTO
    {
        $is_subscription = 'subscription' === $entity->mode;

        $meta = array_merge(
            $entity->metadata ? $entity->metadata->toArray() : array(),
            array(
                Transaction::$meta_key_gateway_transaction => Learndash_Transaction_Gateway_Transaction_DTO::create(
                    array(
                        'id'    => $is_subscription ? $entity->subscription : $entity->payment_intent,
                        'event' => $entity,
                    )
                ),
            )
        );

        $meta = $this->process_legacy_meta(
            $entity,
            $meta,
            $is_subscription,
            $entity->metadata->learndash_version ?? '',
            $product
        );

        // It was encoded to allow arrays in the metadata.
        if (is_string($meta[Transaction::$meta_key_pricing_info])) {
            $meta[Transaction::$meta_key_pricing_info] = json_decode(
                $meta[Transaction::$meta_key_pricing_info],
                true
            );
        }

        return \Learndash_Transaction_Meta_DTO::create($meta);
    }

    /**
     * @return void
     */
    public function enqueue_scripts(): void
    {
        wp_enqueue_style('ldlms-cp-style', LDLMS_CRYPTOPAY_URL . 'assets/css/main.css', array(), LDLMS_CRYPTOPAY_VERSION);
        wp_enqueue_script('ldlms-cp-script', LDLMS_CRYPTOPAY_URL . 'assets/js/main.js', array('jquery'), LDLMS_CRYPTOPAY_VERSION, true);
    }

    /**
     * @return void
     */
    public function process_webhook(): void
    {
    }
}
