<?php
/**
 * J&P Bali — Floating cart + mini cart AJAX
 * Require from moderno-child/functions.php:
 * require_once get_stylesheet_directory() . '/jnp-floating-cart.php';
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'JNP_FLOATING_CART_VER', '1.2.0' );

/**
 * Load asset file from theme directory.
 *
 * @param string $file Relative filename.
 * @return string
 */
function jnp_floating_cart_get_asset( $file ) {
	$path = get_stylesheet_directory() . '/' . ltrim( $file, '/' );
	if ( ! is_readable( $path ) ) {
		return '';
	}
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	return (string) file_get_contents( $path );
}

/**
 * Register assets and footer markup.
 */
function jnp_floating_cart_bootstrap() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	$css = jnp_floating_cart_get_asset( 'jnp-floating-cart.css' );
	$js  = jnp_floating_cart_get_asset( 'jnp-floating-cart.js' );

	wp_register_style( 'jnp-floating-cart', false, array(), JNP_FLOATING_CART_VER );
	wp_enqueue_style( 'jnp-floating-cart' );
	if ( $css ) {
		wp_add_inline_style( 'jnp-floating-cart', $css );
	}

	wp_register_script(
		'jnp-floating-cart',
		false,
		array( 'jquery', 'wc-cart-fragments' ),
		JNP_FLOATING_CART_VER,
		true
	);
	wp_enqueue_script( 'jnp-floating-cart' );
	if ( $js ) {
		wp_add_inline_script( 'jnp-floating-cart', $js );
	}

	wp_localize_script(
		'jnp-floating-cart',
		'jnpFloatingCart',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'jnp_mini_cart' ),
			'cartUrl' => wc_get_cart_url(),
			'i18n'    => array(
				'added'         => __( 'Added to cart!', 'moderno-child' ),
				'wishlistAdded' => __( 'Saved & added to cart!', 'moderno-child' ),
				'selectOptions' => __( 'Please choose design, size & colour first.', 'moderno-child' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'jnp_floating_cart_bootstrap', 100 );

/**
 * Footer floating cart UI.
 */
function jnp_floating_cart_render() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	$count   = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$is_empty = $count === 0;
	?>
	<div class="jnp-float-cart<? echo $is_empty ? ' is-empty' : ''; ?>" id="jnp-float-cart">
		<div class="jnp-float-cart__backdrop" aria-hidden="true"></div>
		<div class="jnp-float-cart__panel" role="dialog" aria-modal="true" aria-label="<? esc_attr_e( 'Shopping cart', 'moderno-child' ); ?>" hidden>
			<div class="jnp-float-cart__head">
				<h2 class="jnp-float-cart__title"><? esc_html_e( 'Your Cart', 'moderno-child' ); ?></h2>
				<button type="button" class="jnp-float-cart__close" aria-label="<? esc_attr_e( 'Close cart', 'moderno-child' ); ?>">&times;</button>
			</div>
			<div class="jnp-float-cart__body">
				<p class="jnp-float-cart__empty"><? esc_html_e( 'Your cart is empty.', 'moderno-child' ); ?></p>
			</div>
			<div class="jnp-float-cart__foot">
				<p class="jnp-float-cart__subtotal"></p>
				<a class="jnp-float-cart__checkout" href="<? echo esc_url( wc_get_checkout_url() ); ?>">
					<? esc_html_e( 'Checkout', 'moderno-child' ); ?>
				</a>
				<a class="jnp-float-cart__view-cart" href="<? echo esc_url( wc_get_cart_url() ); ?>">
					<? esc_html_e( 'View full cart', 'moderno-child' ); ?>
				</a>
			</div>
		</div>
		<button type="button" class="jnp-float-cart__btn" aria-expanded="false" aria-label="<? esc_attr_e( 'Open shopping cart', 'moderno-child' ); ?>">
			<span class="jnp-float-cart__count" data-count="<? echo esc_attr( (string) $count ); ?>">
				<? echo $count > 0 ? esc_html( (string) $count ) : ''; ?>
			</span>
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M6 6h15l-1.5 9h-12z"></path>
				<circle cx="9" cy="20" r="1.5"></circle>
				<circle cx="18" cy="20" r="1.5"></circle>
				<path d="M6 6L5 3H2"></path>
			</svg>
		</button>
	</div>
	<div class="jnp-float-cart-toast" id="jnp-float-cart-toast" role="status" aria-live="polite"></div>
	<?php
}
add_action( 'wp_footer', 'jnp_floating_cart_render', 25 );

/**
 * AJAX: mini cart HTML + count.
 */
function jnp_ajax_mini_cart() {
	check_ajax_referer( 'jnp_mini_cart', 'nonce' );

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => 'Cart unavailable' ), 400 );
	}

	$count = WC()->cart->get_cart_contents_count();

	if ( $count === 0 ) {
		wp_send_json_success(
			array(
				'count'    => 0,
				'html'     => '<p class="jnp-float-cart__empty">' . esc_html__( 'Your cart is empty.', 'moderno-child' ) . '</p>',
				'subtotal' => '',
			)
		);
	}

	ob_start();
	woocommerce_mini_cart();
	$html = ob_get_clean();

	$subtotal = WC()->cart->get_cart_subtotal();

	wp_send_json_success(
		array(
			'count'    => $count,
			'html'     => $html,
			'subtotal' => sprintf(
				/* translators: %s: cart subtotal */
				__( 'Subtotal: %s', 'moderno-child' ),
				'<strong>' . wp_kses_post( $subtotal ) . '</strong>'
			),
		)
	);
}
add_action( 'wp_ajax_jnp_mini_cart', 'jnp_ajax_mini_cart' );
add_action( 'wp_ajax_nopriv_jnp_mini_cart', 'jnp_ajax_mini_cart' );

/**
 * Ensure AJAX add-to-cart is enabled on shop pages.
 */
function jnp_enable_ajax_add_to_cart() {
	add_filter( 'woocommerce_loop_add_to_cart_link', 'jnp_loop_add_to_cart_link', 10, 3 );
}
add_action( 'init', 'jnp_enable_ajax_add_to_cart' );

function jnp_loop_add_to_cart_link( $link, $product, $args ) {
	if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		$link = sprintf(
			'<a href="%s" data-quantity="%s" class="%s" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( $product->add_to_cart_description() ),
			esc_html( $product->add_to_cart_text() )
		);
	}
	return $link;
}
