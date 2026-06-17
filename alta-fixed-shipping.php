<?php
/**
 * Plugin Name:       Alta Fixed Shipping
 * Plugin URI:        https://github.com/rajasj08/wordpress-alta-fixed-shipping
 * Description:       Adds a WooCommerce shipping method that calculates shipping cost based on total cart quantity using fixed freight brackets.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            Rajesh Sundaram
 * Author URI:        https://github.com/rajasj08
 * Text Domain:       alta-fixed-shipping
 * Domain Path:       /languages
 * WC requires at least: 8.0
 * WC tested up to:   9.0
 *
 * @package AltaFixedShipping
 */

defined( 'ABSPATH' ) || exit;

define( 'ALTA_FIXED_SHIPPING_VERSION', '1.0.0' );
define( 'ALTA_FIXED_SHIPPING_PLUGIN_FILE', __FILE__ );
define( 'ALTA_FIXED_SHIPPING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Bootstrap the plugin after all plugins are loaded.
 *
 * @return void
 */
function alta_fixed_shipping_init() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'alta_fixed_shipping_wc_missing_notice' );
		return;
	}

	add_action( 'woocommerce_shipping_init', 'alta_fixed_shipping_load_shipping_method' );
	add_filter( 'woocommerce_shipping_methods', 'alta_fixed_shipping_register_shipping_method' );
}

add_action( 'plugins_loaded', 'alta_fixed_shipping_init' );

/**
 * Display an admin notice when WooCommerce is not active.
 *
 * @return void
 */
function alta_fixed_shipping_wc_missing_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$message = sprintf(
		/* translators: %s: WooCommerce plugin name. */
		esc_html__( 'Alta Fixed Shipping requires %s to be installed and active.', 'alta-fixed-shipping' ),
		'<strong>WooCommerce</strong>'
	);

	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		wp_kses_post( $message )
	);
}

/**
 * Load the shipping method class file.
 *
 * @return void
 */
function alta_fixed_shipping_load_shipping_method() {
	require_once ALTA_FIXED_SHIPPING_PLUGIN_DIR . 'includes/class-alta-fixed-shipping-method.php';
}

/**
 * Register the Alta Fixed Shipping method with WooCommerce.
 *
 * @param array<string, string> $methods Registered shipping methods.
 * @return array<string, string>
 */
function alta_fixed_shipping_register_shipping_method( $methods ) {
	$methods['alta_fixed_shipping'] = AltaFixedShipping\Alta_Fixed_Shipping_Method::class;

	return $methods;
}
