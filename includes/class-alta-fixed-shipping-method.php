<?php
/**
 * Alta Fixed Shipping method.
 *
 * @package AltaFixedShipping
 */

namespace AltaFixedShipping;

defined( 'ABSPATH' ) || exit;

/**
 * Shipping method that calculates cost from total cart quantity brackets.
 */
class Alta_Fixed_Shipping_Method extends \WC_Shipping_Method {

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'alta_fixed_shipping';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'Alta Fixed Shipping', 'alta-fixed-shipping' );
		$this->method_description = __( 'Calculate shipping based on total cart quantity using fixed freight brackets.', 'alta-fixed-shipping' );

		$this->supports = array(
			'shipping-zones',
			'instance-settings',
		);

		$this->init();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Initialize settings and form fields.
	 *
	 * @return void
	 */
	public function init() {
		$this->init_form_fields();
		$this->init_settings();

		$this->enabled = $this->get_option( 'enabled' );
		$this->title   = $this->get_option( 'title' );
	}

	/**
	 * Define admin settings fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->instance_form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'alta-fixed-shipping' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this shipping method', 'alta-fixed-shipping' ),
				'default' => 'yes',
			),
			'title'   => array(
				'title'       => __( 'Method Title', 'alta-fixed-shipping' ),
				'type'        => 'text',
				'description' => __( 'Title displayed to customers at checkout.', 'alta-fixed-shipping' ),
				'default'     => __( 'Alta Fixed Shipping', 'alta-fixed-shipping' ),
				'desc_tip'    => true,
			),
		);
	}

	/**
	 * Save admin settings.
	 *
	 * @return bool
	 */
	public function process_admin_options() {
		return parent::process_admin_options();
	}

	/**
	 * Calculate shipping rates for a package.
	 *
	 * @param array<string, mixed> $package Package of cart items.
	 * @return void
	 */
	public function calculate_shipping( $package = array() ) {
		if ( ! $this->is_available( $package ) ) {
			return;
		}

		$quantity = $this->get_package_quantity( $package );

		if ( $quantity <= 0 ) {
			return;
		}

		$cost = $this->calculate_freight_cost( $quantity );

		$this->add_rate(
			array(
				'id'      => $this->get_rate_id(),
				'label'   => $this->title,
				'cost'    => $cost,
				'package' => $package,
			)
		);
	}

	/**
	 * Sum quantities of all items in the package.
	 *
	 * Includes simple products and product variations.
	 *
	 * @param array<string, mixed> $package Package of cart items.
	 * @return int
	 */
	private function get_package_quantity( $package ) {
		$quantity = 0;

		if ( empty( $package['contents'] ) || ! is_array( $package['contents'] ) ) {
			return 0;
		}

		foreach ( $package['contents'] as $item ) {
			if ( isset( $item['quantity'] ) ) {
				$quantity += absint( $item['quantity'] );
			}
		}

		return $quantity;
	}

	/**
	 * Calculate freight cost from total quantity using Alta brackets.
	 *
	 * Quantity 1:        $18
	 * Quantity 2–6:       $20
	 * Quantity 7+:        $28 base, plus $8 for each additional 6-unit bracket
	 *
	 * @param int $quantity Total cart quantity.
	 * @return float
	 */
	private function calculate_freight_cost( $quantity ) {
		$quantity = absint( $quantity );

		if ( $quantity <= 0 ) {
			return 0.0;
		}

		if ( 1 === $quantity ) {
			return 18.0;
		}

		if ( $quantity <= 6 ) {
			return 20.0;
		}

		// Bracket 0 covers 7–12, bracket 1 covers 13–18, and so on.
		$bracket = (int) floor( ( $quantity - 7 ) / 6 );

		return 28.0 + ( $bracket * 8 );
	}
}
