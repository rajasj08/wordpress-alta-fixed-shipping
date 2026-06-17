=== Alta Fixed Shipping ===
Contributors: rajeshsundaram
Tags: woocommerce, shipping, freight, fixed shipping
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a WooCommerce shipping method that calculates shipping cost based on total cart quantity using fixed freight brackets.

== Description ==

Alta Fixed Shipping provides a custom WooCommerce shipping method that ignores product weight, dimensions, shipping classes, and subtotal. Instead, it calculates shipping based on the total quantity of all items in the cart.

**Shipping brackets:**

* 1 item — $18
* 2–6 items — $20
* 7–12 items — $28
* 13–18 items — $36
* 19–24 items — $44
* 25–30 items — $52
* 31+ items — add $8 for every additional block of 6 units

**Features:**

* Enable or disable the method per shipping zone
* Customize the title shown to customers at checkout
* Works with simple and variable products
* Dynamically recalculates during cart and checkout updates

== Installation ==

1. Upload the `alta-fixed-shipping` folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Ensure WooCommerce is installed and active.
4. Go to **WooCommerce → Settings → Shipping → Shipping Zones**.
5. Edit a zone and click **Add shipping method**.
6. Select **Alta Fixed Shipping**, then configure and enable it.

== Frequently Asked Questions ==

= Does this method use product weight or dimensions? =

No. Shipping is calculated solely from the total quantity of items in the cart.

= Are variable product quantities included? =

Yes. All cart line item quantities, including product variations, are summed together.

= What happens if the cart is empty? =

No shipping rate is returned when the cart has no items.

== Changelog ==

= 1.0.0 =
* Initial release.
