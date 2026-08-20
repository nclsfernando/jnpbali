<?php
/**
 * Plugin Name: J&P Bali Floating Cart
 * Plugin URI: https://jnpbali.com
 * Description: Floating cart button with mini-cart panel and wishlist-to-cart integration.
 * Version: 1.1.1
 * Author: J&P Bali
 * Requires Plugins: woocommerce
 * Text Domain: jnp-floating-cart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'JNP_FLOATING_CART_VERSION', '1.1.1' );

function jnp_floating_cart_get_css() {
	return <<<'JNP_CSS'
/* J&P Bali â€” Floating cart v1.1 (above WhatsApp, below cookie modal) */
:root {
	--jnp-float-cart-bottom: 168px;
	--jnp-float-cart-bottom-mobile: 160px;
	--jnp-gold: #d4af37;
	--jnp-black: #050505;
}

#jnp-float-cart,
#jnp-float-cart *,
#jnp-float-cart-toast {
	box-sizing: border-box;
}

#jnp-float-cart {
	position: fixed !important;
	right: 18px !important;
	left: auto !important;
	bottom: var(--jnp-float-cart-bottom) !important;
	top: auto !important;
	width: auto !important;
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
	z-index: 999998 !important;
	font-family: "Inter", sans-serif;
	pointer-events: none;
}

#jnp-float-cart .jnp-float-cart__btn {
	pointer-events: auto;
}

#jnp-float-cart .jnp-float-cart__panel,
#jnp-float-cart .jnp-float-cart__backdrop {
	pointer-events: auto;
}

#jnp-float-cart .jnp-float-cart__btn {
	position: relative;
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 54px;
	height: 54px;
	min-width: 54px;
	min-height: 54px;
	border: none !important;
	border-radius: 50% !important;
	background: var(--jnp-gold) !important;
	color: var(--jnp-black) !important;
	box-shadow: 0 6px 18px rgba(0, 0, 0, 0.45);
	cursor: pointer;
	padding: 0 !important;
	line-height: 1 !important;
	transition: transform 0.18s ease, box-shadow 0.18s ease;
	-webkit-appearance: none;
	appearance: none;
}

#jnp-float-cart .jnp-float-cart__btn:hover,
#jnp-float-cart .jnp-float-cart__btn:focus-visible {
	transform: translateY(-2px);
	box-shadow: 0 10px 24px rgba(0, 0, 0, 0.55);
	outline: 2px solid rgba(255, 255, 255, 0.35);
	outline-offset: 2px;
}

#jnp-float-cart .jnp-float-cart__btn svg {
	display: block;
	width: 26px;
	height: 26px;
	flex-shrink: 0;
}

#jnp-float-cart .jnp-float-cart__count {
	position: absolute;
	top: -2px;
	right: -2px;
	min-width: 20px;
	height: 20px;
	padding: 0 6px;
	border-radius: 999px;
	background: #fff !important;
	color: var(--jnp-black) !important;
	font-size: 11px;
	font-weight: 700;
	line-height: 20px;
	text-align: center;
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
}

#jnp-float-cart .jnp-float-cart__count:empty,
#jnp-float-cart .jnp-float-cart__count[data-count="0"] {
	display: none !important;
}

/* Mini cart panel â€” hidden until opened */
#jnp-float-cart .jnp-float-cart__panel {
	display: none !important;
	position: fixed !important;
	right: 18px !important;
	left: auto !important;
	bottom: calc(var(--jnp-float-cart-bottom) + 64px) !important;
	top: auto !important;
	width: min(360px, calc(100vw - 36px));
	max-height: min(70vh, 520px);
	background: #111 !important;
	border: 1px solid rgba(212, 175, 55, 0.35);
	border-radius: 14px;
	box-shadow: 0 16px 40px rgba(0, 0, 0, 0.65);
	overflow: hidden;
	flex-direction: column;
	z-index: 999999 !important;
	text-align: left !important;
}

#jnp-float-cart.is-open .jnp-float-cart__panel {
	display: flex !important;
}

#jnp-float-cart .jnp-float-cart__head {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	padding: 14px 16px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	flex-shrink: 0;
}

#jnp-float-cart .jnp-float-cart__title {
	margin: 0 !important;
	padding: 0 !important;
	font-family: "Space Grotesk", sans-serif;
	font-size: 16px !important;
	font-weight: 700 !important;
	color: #fff !important;
	line-height: 1.3 !important;
	text-align: left !important;
}

#jnp-float-cart .jnp-float-cart__close {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 32px;
	min-width: 32px;
	border: 1px solid rgba(255, 255, 255, 0.2) !important;
	border-radius: 50% !important;
	background: rgba(255, 255, 255, 0.1) !important;
	color: #fff !important;
	font-size: 20px !important;
	line-height: 1 !important;
	cursor: pointer;
	padding: 0 !important;
	flex-shrink: 0;
	-webkit-appearance: none;
	appearance: none;
}

#jnp-float-cart .jnp-float-cart__close:hover,
#jnp-float-cart .jnp-float-cart__close:focus-visible {
	background: rgba(255, 255, 255, 0.18) !important;
	outline: none;
}

#jnp-float-cart .jnp-float-cart__body {
	padding: 12px 16px;
	overflow-y: auto;
	flex: 1 1 auto;
	min-height: 72px;
	color: #e8e8e8 !important;
	text-align: left !important;
}

#jnp-float-cart .jnp-float-cart__body .woocommerce-mini-cart {
	margin: 0 !important;
	padding: 0 !important;
	list-style: none !important;
}

#jnp-float-cart .jnp-float-cart__body .woocommerce-mini-cart-item {
	display: flex;
	gap: 10px;
	margin: 0 0 12px !important;
	padding: 0 0 12px !important;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	font-size: 13px;
	line-height: 1.45;
}

#jnp-float-cart .jnp-float-cart__body .woocommerce-mini-cart-item img {
	width: 52px;
	height: 52px;
	object-fit: cover;
	border-radius: 8px;
}

#jnp-float-cart .jnp-float-cart__body .woocommerce-mini-cart-item a {
	color: #fff !important;
	text-decoration: none !important;
}

#jnp-float-cart .jnp-float-cart__body .quantity {
	color: #b0b0b0 !important;
}

#jnp-float-cart .jnp-float-cart__empty {
	margin: 0 !important;
	padding: 12px 0 !important;
	color: #ccc !important;
	font-size: 14px !important;
	text-align: center !important;
}

#jnp-float-cart .jnp-float-cart__foot {
	padding: 12px 16px 16px;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
	flex-shrink: 0;
	background: #111;
}

#jnp-float-cart .jnp-float-cart__subtotal {
	margin: 0 0 12px !important;
	font-size: 14px !important;
	color: #e8e8e8 !important;
	text-align: left !important;
}

#jnp-float-cart .jnp-float-cart__subtotal:empty {
	display: none;
}

#jnp-float-cart .jnp-float-cart__subtotal strong {
	color: var(--jnp-gold) !important;
}

#jnp-float-cart .jnp-float-cart__checkout {
	display: block !important;
	width: 100%;
	text-align: center !important;
	background: var(--jnp-gold) !important;
	color: var(--jnp-black) !important;
	font-weight: 700 !important;
	font-size: 13px !important;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	text-decoration: none !important;
	padding: 12px 16px !important;
	border-radius: 999px !important;
	border: none !important;
	line-height: 1.3 !important;
}

#jnp-float-cart.is-empty .jnp-float-cart__checkout {
	display: none !important;
}

#jnp-float-cart .jnp-float-cart__view-cart {
	display: block !important;
	margin-top: 8px;
	text-align: center !important;
	color: #ccc !important;
	font-size: 12px !important;
	text-decoration: underline !important;
	line-height: 1.4 !important;
}

#jnp-float-cart.is-empty .jnp-float-cart__view-cart {
	margin-top: 0;
}

/* Toast */
#jnp-float-cart-toast {
	position: fixed !important;
	right: 18px !important;
	left: auto !important;
	bottom: calc(var(--jnp-float-cart-bottom) + 70px) !important;
	top: auto !important;
	z-index: 1000000 !important;
	background: #1a1a1a !important;
	color: #fff !important;
	border: 1px solid rgba(212, 175, 55, 0.5);
	border-radius: 10px;
	padding: 10px 14px;
	font-size: 13px;
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
	opacity: 0;
	visibility: hidden;
	transform: translateY(8px);
	transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
	pointer-events: none;
	max-width: min(320px, calc(100vw - 36px));
	text-align: left !important;
}

#jnp-float-cart-toast.is-visible {
	opacity: 1;
	visibility: visible;
	transform: translateY(0);
}

#jnp-float-cart .jnp-float-cart__backdrop {
	display: none !important;
}

@media (max-width: 767px) {
	#jnp-float-cart {
		bottom: var(--jnp-float-cart-bottom-mobile) !important;
		right: 14px !important;
	}

	#jnp-float-cart .jnp-float-cart__panel {
		right: 14px !important;
		left: 14px !important;
		width: auto !important;
		bottom: calc(var(--jnp-float-cart-bottom-mobile) + 64px) !important;
	}

	#jnp-float-cart-toast {
		right: 14px !important;
		bottom: calc(var(--jnp-float-cart-bottom-mobile) + 70px) !important;
	}

	#jnp-float-cart.is-open .jnp-float-cart__backdrop {
		display: block !important;
		position: fixed !important;
		inset: 0 !important;
		background: rgba(0, 0, 0, 0.5);
		z-index: 999997 !important;
		pointer-events: auto;
	}
}

@media print {
	#jnp-float-cart,
	#jnp-float-cart-toast {
		display: none !important;
	}
}

@media (prefers-reduced-motion: reduce) {
	#jnp-float-cart .jnp-float-cart__btn,
	#jnp-float-cart-toast {
		transition: none;
	}
}

JNP_CSS;
}

function jnp_floating_cart_get_js() {
	return <<<'JNP_JS'
(function ($) {
	"use strict";

	if (typeof jnpFloatingCart === "undefined") {
		return;
	}

	var root = document.getElementById("jnp-float-cart");
	if (!root) {
		return;
	}

	var btn = root.querySelector(".jnp-float-cart__btn");
	var panel = root.querySelector(".jnp-float-cart__panel");
	var closeBtn = root.querySelector(".jnp-float-cart__close");
	var backdrop = root.querySelector(".jnp-float-cart__backdrop");
	var countEl = root.querySelector(".jnp-float-cart__count");
	var bodyEl = root.querySelector(".jnp-float-cart__body");
	var subtotalEl = root.querySelector(".jnp-float-cart__subtotal");
	var toastEl = document.getElementById("jnp-float-cart-toast");
	var openOnAdd = true;

	function showToast(message) {
		if (!toastEl) {
			return;
		}
		toastEl.textContent = message;
		toastEl.classList.add("is-visible");
		window.clearTimeout(showToast._t);
		showToast._t = window.setTimeout(function () {
			toastEl.classList.remove("is-visible");
		}, 2800);
	}

	function setEmptyState(count) {
		var n = parseInt(count, 10) || 0;
		root.classList.toggle("is-empty", n === 0);
	}

	function setCount(count) {
		var n = parseInt(count, 10) || 0;
		countEl.textContent = n > 0 ? String(n) : "";
		countEl.setAttribute("data-count", String(n));
		countEl.style.display = n > 0 ? "" : "none";
		setEmptyState(n);

		document.querySelectorAll(".jnp-cart-link__count").forEach(function (el) {
			el.textContent = n > 0 ? String(n) : "0";
		});

		document.querySelectorAll(".jnp-cart-link").forEach(function (link) {
			link.setAttribute(
				"aria-label",
				n === 1 ? "Cart, 1 item" : "Cart, " + n + " items"
			);
		});
	}

	function syncCountFromHeader() {
		var max = 0;
		document.querySelectorAll(".jnp-cart-link__count").forEach(function (el) {
			var n = parseInt(el.textContent, 10) || 0;
			if (n > max) {
				max = n;
			}
		});
		setCount(max);
	}

	function refreshMiniCart(openPanel) {
		return $.post(jnpFloatingCart.ajaxUrl, {
			action: "jnp_mini_cart",
			nonce: jnpFloatingCart.nonce,
		})
			.done(function (response) {
				if (!response || !response.success) {
					return;
				}
				bodyEl.innerHTML = response.data.html || "";
				if (subtotalEl) {
					subtotalEl.innerHTML = response.data.subtotal || "";
				}
				setCount(response.data.count);
				if (openPanel) {
					openPanelFn();
				}
			})
			.fail(function () {
				showToast("Could not refresh cart. Please try again.");
			});
	}

	function openPanelFn() {
		root.classList.add("is-open");
		btn.setAttribute("aria-expanded", "true");
		if (panel) {
			panel.removeAttribute("hidden");
		}
	}

	function closePanelFn() {
		root.classList.remove("is-open");
		btn.setAttribute("aria-expanded", "false");
		if (panel) {
			panel.setAttribute("hidden", "hidden");
		}
	}

	btn.addEventListener("click", function () {
		if (root.classList.contains("is-open")) {
			closePanelFn();
			return;
		}
		refreshMiniCart(true);
	});

	if (closeBtn) {
		closeBtn.addEventListener("click", closePanelFn);
	}
	if (backdrop) {
		backdrop.addEventListener("click", closePanelFn);
	}

	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape") {
			closePanelFn();
		}
	});

	$(document.body).on("added_to_cart", function (e, fragments) {
		if (fragments) {
			$.each(fragments, function (key, value) {
				$(key).replaceWith(value);
			});
		}
		syncCountFromHeader();
		refreshMiniCart(openOnAdd);
		showToast(jnpFloatingCart.i18n.added);
	});

	$(document.body).on(
		"removed_from_cart wc_fragments_refreshed",
		function () {
			syncCountFromHeader();
			if (root.classList.contains("is-open")) {
				refreshMiniCart(false);
			}
		}
	);

	$(document).on("click", "a.add_to_cart_button", function () {
		openOnAdd = true;
	});

	$(document).on("submit", "form.cart", function () {
		openOnAdd = true;
	});

	$(document).on("click", ".js-wishlist-btn", function () {
		if (!$("body").hasClass("single-product")) {
			return;
		}

		var $form = $("form.variations_form, form.cart").first();
		if (!$form.length) {
			return;
		}

		window.setTimeout(function () {
			var $addBtn = $form.find(".single_add_to_cart_button");
			if (!$addBtn.length || $addBtn.hasClass("disabled")) {
				showToast(jnpFloatingCart.i18n.selectOptions);
				return;
			}

			if ($form.hasClass("variations_form")) {
				var variationId = $form.find('input[name="variation_id"]').val();
				if (!variationId || variationId === "0") {
					showToast(jnpFloatingCart.i18n.selectOptions);
					return;
				}
			}

			openOnAdd = true;
			$addBtn.trigger("click");
			showToast(jnpFloatingCart.i18n.wishlistAdded);
		}, 120);
	});

	$(document).on(
		"click",
		".c-wishlist a.add_to_cart_button, .c-wishlist .add_to_cart_button",
		function () {
			openOnAdd = true;
		}
	);

	closePanelFn();
	syncCountFromHeader();
	refreshMiniCart(false);
})(jQuery);

JNP_JS;
}

function jnp_floating_cart_bootstrap() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	wp_register_style( 'jnp-floating-cart', false, array(), JNP_FLOATING_CART_VERSION );
	wp_enqueue_style( 'jnp-floating-cart' );
	wp_add_inline_style( 'jnp-floating-cart', jnp_floating_cart_get_css() );

	wp_register_script( 'jnp-floating-cart', false, array( 'jquery', 'wc-cart-fragments' ), JNP_FLOATING_CART_VERSION, true );
	wp_enqueue_script( 'jnp-floating-cart' );
	wp_add_inline_script( 'jnp-floating-cart', jnp_floating_cart_get_js() );

	wp_localize_script(
		'jnp-floating-cart',
		'jnpFloatingCart',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'jnp_mini_cart' ),
			'cartUrl' => wc_get_cart_url(),
			'i18n'    => array(
				'added'         => __( 'Added to cart!', 'jnp-floating-cart' ),
				'wishlistAdded' => __( 'Saved & added to cart!', 'jnp-floating-cart' ),
				'selectOptions' => __( 'Please choose design, size & colour first.', 'jnp-floating-cart' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'jnp_floating_cart_bootstrap', 100 );

function jnp_floating_cart_render() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	$count    = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$is_empty = $count === 0;
	?>
	<div class="jnp-float-cart<?php echo $is_empty ? ' is-empty' : ''; ?>" id="jnp-float-cart">
		<div class="jnp-float-cart__backdrop" aria-hidden="true"></div>
		<div class="jnp-float-cart__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Shopping cart', 'jnp-floating-cart' ); ?>" hidden>
			<div class="jnp-float-cart__head">
				<h2 class="jnp-float-cart__title"><?php esc_html_e( 'Your Cart', 'jnp-floating-cart' ); ?></h2>
				<button type="button" class="jnp-float-cart__close" aria-label="<?php esc_attr_e( 'Close cart', 'jnp-floating-cart' ); ?>">&times;</button>
			</div>
			<div class="jnp-float-cart__body">
				<p class="jnp-float-cart__empty"><?php esc_html_e( 'Your cart is empty.', 'jnp-floating-cart' ); ?></p>
			</div>
			<div class="jnp-float-cart__foot">
				<p class="jnp-float-cart__subtotal"></p>
				<a class="jnp-float-cart__checkout" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Checkout', 'jnp-floating-cart' ); ?></a>
				<a class="jnp-float-cart__view-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'View full cart', 'jnp-floating-cart' ); ?></a>
			</div>
		</div>
		<button type="button" class="jnp-float-cart__btn" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open shopping cart', 'jnp-floating-cart' ); ?>">
			<span class="jnp-float-cart__count" data-count="<?php echo esc_attr( (string) $count ); ?>"><?php echo $count > 0 ? esc_html( (string) $count ) : ''; ?></span>
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"></path><circle cx="9" cy="20" r="1.5"></circle><circle cx="18" cy="20" r="1.5"></circle><path d="M6 6L5 3H2"></path></svg>
		</button>
	</div>
	<div class="jnp-float-cart-toast" id="jnp-float-cart-toast" role="status" aria-live="polite"></div>
	<?php
}
add_action( 'wp_footer', 'jnp_floating_cart_render', 25 );

function jnp_ajax_mini_cart() {
	check_ajax_referer( 'jnp_mini_cart', 'nonce' );
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => 'Cart unavailable' ), 400 );
	}
	$count = WC()->cart->get_cart_contents_count();
	if ( $count === 0 ) {
		wp_send_json_success( array(
			'count'    => 0,
			'html'     => '<p class="jnp-float-cart__empty">' . esc_html__( 'Your cart is empty.', 'jnp-floating-cart' ) . '</p>',
			'subtotal' => '',
		) );
	}
	ob_start();
	woocommerce_mini_cart();
	$html = ob_get_clean();
	wp_send_json_success( array(
		'count'    => $count,
		'html'     => $html,
		'subtotal' => sprintf( __( 'Subtotal: %s', 'jnp-floating-cart' ), '<strong>' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '</strong>' ),
	) );
}
add_action( 'wp_ajax_jnp_mini_cart', 'jnp_ajax_mini_cart' );
add_action( 'wp_ajax_nopriv_jnp_mini_cart', 'jnp_ajax_mini_cart' );

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
