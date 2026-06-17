# Alta Fixed Shipping

A WooCommerce shipping plugin that calculates shipping cost from the **total quantity of items in the cart**, using fixed freight brackets. Product weight, dimensions, shipping classes, and cart subtotal are not used.

**Author:** Rajesh Sundaram  
**Version:** 1.0.0

## Requirements

- WordPress 6.x
- WooCommerce 8.0+
- PHP 8.1+

## Installation

1. Download or clone this repository into `wp-content/plugins/alta-fixed-shipping/`.
2. Activate **Alta Fixed Shipping** under **Plugins** in WordPress admin.
3. Go to **WooCommerce → Settings → Shipping → Shipping Zones**.
4. Edit a shipping zone and click **Add shipping method**.
5. Choose **Alta Fixed Shipping**, enable it, and set the customer-facing title.

## How It Works

When a customer views the cart or checkout, WooCommerce asks each enabled shipping method in the matching zone to calculate a rate. Alta Fixed Shipping:

1. Sums the quantity of every line item in the cart (simple products and variations included).
2. Maps that total quantity to a fixed freight cost using the brackets below.
3. Returns a single shipping rate with the configured method title.

If the cart is empty, no rate is returned. The cost recalculates automatically whenever the cart is updated.

### What is ignored

- Product weight and dimensions
- Shipping classes
- Cart subtotal or product price
- Individual product types (only total quantity matters)

### Shipping brackets

| Total quantity | Shipping cost |
| -------------- | ------------- |
| 1              | $18           |
| 2–6            | $20           |
| 7–12           | $28           |
| 13–18          | $36           |
| 19–24          | $44           |
| 25–30          | $52           |
| 31–36          | $60           |
| …              | +$8 per additional block of 6 units |

### Examples

| Cart items | Total qty | Shipping |
| ---------- | --------- | -------- |
| 1 × Product A | 1 | $18 |
| 4 × Product A | 4 | $20 |
| 6 × Product A | 6 | $20 |
| 11 × Product A | 11 | $28 |
| 13 × Product A | 13 | $36 |
| 24 × Product A | 24 | $44 |
| 31 × Product A | 31 | $60 |

Mixed carts work the same way: quantities across all products are added together. For example, 3 × Product A + 2 × Product B = 5 items → $20 shipping.

### Calculation formula

For quantities of **7 or more**, the plugin uses a 6-unit bracket formula:

```
bracket = floor((quantity - 7) / 6)
cost    = 28 + (bracket × 8)
```

Bracket 0 covers 7–12 units, bracket 1 covers 13–18, and so on.

## Admin settings

Each shipping zone instance has two settings:

| Setting | Description |
| ------- | ----------- |
| **Enable/Disable** | Turn the method on or off for that zone |
| **Method Title** | Label shown to customers at checkout (default: *Alta Fixed Shipping*) |

## Plugin structure

```
alta-fixed-shipping/
├── alta-fixed-shipping.php              # Bootstrap, WooCommerce check, hooks
├── includes/
│   └── class-alta-fixed-shipping-method.php  # WC_Shipping_Method implementation
├── readme.txt                           # WordPress.org-style readme
├── uninstall.php
└── README.md
```

### Key files

- **`alta-fixed-shipping.php`** — Loads only when WooCommerce is active. Registers the shipping method via `woocommerce_shipping_init` and `woocommerce_shipping_methods`.
- **`class-alta-fixed-shipping-method.php`** — Extends `WC_Shipping_Method`, supports shipping zones and per-instance settings. Core logic lives in `calculate_shipping()` and the private `calculate_freight_cost()` method.

## Compatibility

- Simple and variable products (variation quantities are included in the total)
- WooCommerce shipping zones
- Dynamic recalculation on cart and checkout updates

## License

GPLv2 or later. See [readme.txt](readme.txt) for details.
