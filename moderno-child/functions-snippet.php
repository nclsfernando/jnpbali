<?php
/**
 * Enqueue ui-fixes.css — tambahkan di AKHIR functions.php (moderno-child)
 * v23.9 — Aug 2026
 *
 * Juga: header-custom.js (WA number, lightbox guard, hotel sync),
 * free hotel delivery, Stripe label.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'jnp-ui-fixes',
			get_stylesheet_directory_uri() . '/ui-fixes.css',
			array(),
			'23.9.0'
		);
	},
	99
);

/**
 * Floating cart + wishlist → cart integration
 */
require_once get_stylesheet_directory() . '/jnp-floating-cart.php';
require_once get_stylesheet_directory() . '/jnp-international-sizes.php';
require_once get_stylesheet_directory() . '/jnp-shop-fixes.php';

/**
 * Shop/product copy promises free hotel delivery across Bali.
 * Zero WooCommerce package rates so checkout matches that promise.
 */
add_filter(
	'woocommerce_package_rates',
	function ( $rates ) {
		foreach ( $rates as $rate_id => $rate ) {
			$rates[ $rate_id ]->cost = 0;
			$rates[ $rate_id ]->label = __( 'Free hotel delivery (Bali)', 'moderno-child' );
			if ( isset( $rates[ $rate_id ]->taxes ) && is_array( $rates[ $rate_id ]->taxes ) ) {
				foreach ( $rates[ $rate_id ]->taxes as $tax_id => $tax ) {
					$rates[ $rate_id ]->taxes[ $tax_id ] = 0;
				}
			}
		}
		return $rates;
	},
	100
);

add_filter(
	'woocommerce_gateway_title',
	function ( $title, $id ) {
		if ( 'stripe' === $id || 'payment_options' === strtolower( (string) $title ) ) {
			return __( 'Credit Card', 'moderno-child' );
		}
		return $title;
	},
	10,
	2
);

add_filter(
	'woocommerce_checkout_fields',
	function ( $fields ) {
		foreach ( array( 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_state' ) as $key ) {
			if ( isset( $fields['billing'][ $key ] ) ) {
				$fields['billing'][ $key ]['required'] = false;
			}
		}
		return $fields;
	}
);
