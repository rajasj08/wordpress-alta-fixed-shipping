<?php
/**
 * Uninstall Alta Fixed Shipping.
 *
 * @package AltaFixedShipping
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/*
 * WooCommerce stores shipping method instance settings in wp_options
 * (e.g. woocommerce_alta_fixed_shipping_{instance_id}_settings) and manages
 * them through shipping zones. No global plugin options are created by this
 * plugin, so no additional cleanup is required on uninstall.
 */
