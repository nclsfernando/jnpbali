<?php
/**
 * JNP Bali Float Cart - Must-Use drop-in (no activation needed).
 * Upload to: wp-content/mu-plugins/jnpbali-float-cart.php
 * Version: 1.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'JNPBALI_FC_LOADED' ) ) {
	return;
}
define( 'JNPBALI_FC_LOADED', true );
define( 'JNPBALI_FC_VERSION', '1.0.5' );

if ( ! function_exists( 'jnpbali_fc_get_css' ) ) {
	function jnpbali_fc_get_css() {
		return <<<'JNPBALI_CSS'
/* J&P Bali â€” Floating cart v1.1 (above WhatsApp, below cookie modal) */
:root {
	--jnp-float-cart-bottom: 168px;
	--jnp-float-cart-bottom-mobile: 160px;
	--jnp-gold: #d4af37;
	--jnp-black: #050505;
}

/* Product pages: sit above sticky Add-to-cart bar + WhatsApp */
body.single-product,
body.h-sticky-atc {
	--jnp-float-cart-bottom: 228px;
	--jnp-float-cart-bottom-mobile: 220px;
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

#jnp-float-cart .jnp-float-cart__panel[hidden] {
	display: none !important;
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

JNPBALI_CSS;
	}
}

if ( ! function_exists( 'jnpbali_fc_get_js' ) ) {
	function jnpbali_fc_get_js() {
		return <<<'JNPBALI_JS'
(function ($) {
	"use strict";

	if (typeof jnpFloatingCart === "undefined") {
		return;
	}

	var initialized = false;

	function portalToBody(el) {
		if (el && el.parentElement !== document.body) {
			document.body.appendChild(el);
		}
	}

	function initFloatCart() {
		if (initialized) {
			return true;
		}

		var root = document.getElementById("jnp-float-cart");
		if (!root) {
			return false;
		}

		portalToBody(root);
		portalToBody(document.getElementById("jnp-float-cart-toast"));

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

	function applyMiniCartData(data, openPanel) {
		bodyEl.innerHTML = data.html || "";
		if (subtotalEl) {
			subtotalEl.innerHTML = data.subtotal || "";
		}
		setCount(data.count);
		if (openPanel) {
			openPanelFn();
		}
		$(document.body).trigger("wc_fragment_refresh");
	}

	function isInWishlist(productId) {
		var cookieName =
			(window.ideapark_wp_vars && ideapark_wp_vars.wishlistCookieName) ||
			"ip-wishlist-items";
		var pattern = new RegExp(
			"(?:^|; )" +
				cookieName.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") +
				"=([^;]*)"
		);
		var match = document.cookie.match(pattern);
		if (!match) {
			return false;
		}
		try {
			var raw = decodeURIComponent(match[1]);
			var items = JSON.parse(raw);
			if (!Array.isArray(items)) {
				items = raw.split(",").filter(Boolean);
			}
			return items.map(String).indexOf(String(productId)) !== -1;
		} catch (err) {
			var fallback = decodeURIComponent(match[1]).split(",").filter(Boolean);
			return fallback.map(String).indexOf(String(productId)) !== -1;
		}
	}

	function findProductForm($btn) {
		var $scopes = [
			$btn.closest(
				".js-quickview-container, .js-quickview-popup, .c-header__callback-wrap--quickview"
			),
			$btn.closest(".c-product-grid__item, .product, .c-product"),
			$("body").hasClass("single-product") ? $(document) : $(),
		];

		var i;
		for (i = 0; i < $scopes.length; i++) {
			if (!$scopes[i].length) {
				continue;
			}
			var $form = $scopes[i].find("form.variations_form, form.cart").first();
			if ($form.length) {
				return $form;
			}
		}
		return $();
	}

	function collectVariationAttributes($form) {
		var attrs = {};
		$form.find('[name^="attribute_"]').each(function () {
			var $field = $(this);
			attrs[$field.attr("name")] = $field.val();
		});
		return attrs;
	}

	function hasRequiredVariation($form) {
		if (!$form.length || !$form.hasClass("variations_form")) {
			return true;
		}

		var missing = false;
		$form.find('[name^="attribute_"]').each(function () {
			var val = $(this).val();
			if (!val || val === "") {
				missing = true;
			}
		});

		var variationId =
			parseInt($form.find('input[name="variation_id"]').val(), 10) || 0;

		return !missing && variationId > 0;
	}

	function handleWishlistCartResponse(response) {
		if (response && response.success) {
			applyMiniCartData(response.data, true);
			showToast(jnpFloatingCart.i18n.wishlistAdded);
			return;
		}

		var code =
			response && response.data && response.data.code
				? response.data.code
				: "";
		if (code === "select_options") {
			showToast(jnpFloatingCart.i18n.selectOptions);
			return;
		}
		showToast(jnpFloatingCart.i18n.addFailed);
	}

	function wishlistAddToCart($btn) {
		var productId = parseInt($btn.data("product-id"), 10);
		if (!productId) {
			return;
		}

		var payload = {
			action: "jnpbali_fc_wishlist_add_to_cart",
			nonce: jnpFloatingCart.nonce,
			product_id: productId,
			quantity: 1,
		};

		var $form = findProductForm($btn);
		if ($form.length) {
			var qty = parseInt(
				$form.find('input.qty, input[name="quantity"]').val(),
				10
			);
			if (qty > 0) {
				payload.quantity = qty;
			}

			if ($form.hasClass("variations_form")) {
				if (!hasRequiredVariation($form)) {
					showToast(jnpFloatingCart.i18n.selectOptions);
					return;
				}
				payload.variation_id =
					parseInt($form.find('input[name="variation_id"]').val(), 10) || 0;
				$.extend(payload, collectVariationAttributes($form));
			}
		}

		openOnAdd = true;
		$.post(jnpFloatingCart.ajaxUrl, payload)
			.done(handleWishlistCartResponse)
			.fail(function (xhr) {
				handleWishlistCartResponse(xhr.responseJSON);
			});
	}

	function refreshMiniCart(openPanel) {
		return $.post(jnpFloatingCart.ajaxUrl, {
			action: "jnpbali_fc_mini_cart",
			nonce: jnpFloatingCart.nonce,
		})
			.done(function (response) {
				if (!response || !response.success) {
					return;
				}
				applyMiniCartData(response.data, openPanel);
			})
			.fail(function () {
				showToast(jnpFloatingCart.i18n.addFailed);
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
		var $btn = $(this);
		var productId = String($btn.data("product-id") || "");
		var addingToWishlist = productId && !isInWishlist(productId);

		window.setTimeout(function () {
			if (!addingToWishlist) {
				return;
			}
			wishlistAddToCart($btn);
		}, 100);
	});

	closePanelFn();
	syncCountFromHeader();
	refreshMiniCart(false);

	initialized = true;
	return true;
	}

	function bootFloatCart() {
		if (initFloatCart()) {
			return;
		}

		function retry() {
			initFloatCart();
		}

		if (document.readyState === "loading") {
			document.addEventListener("DOMContentLoaded", retry);
		} else {
			retry();
		}

		window.addEventListener("load", retry);
	}

	bootFloatCart();
})(jQuery);

JNPBALI_JS;
	}
}

if ( ! function_exists( 'jnpbali_fc_bootstrap' ) ) {
	function jnpbali_fc_bootstrap() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}
		wp_register_style( 'jnpbali-float-cart', false, array(), JNPBALI_FC_VERSION );
		wp_enqueue_style( 'jnpbali-float-cart' );
		wp_add_inline_style( 'jnpbali-float-cart', jnpbali_fc_get_css() );
		wp_register_script( 'jnpbali-float-cart', false, array( 'jquery', 'wc-cart-fragments' ), JNPBALI_FC_VERSION, true );
		wp_enqueue_script( 'jnpbali-float-cart' );
		wp_add_inline_script( 'jnpbali-float-cart', jnpbali_fc_get_js() );
		wp_localize_script( 'jnpbali-float-cart', 'jnpFloatingCart', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'jnpbali_fc_mini_cart' ),
			'cartUrl' => wc_get_cart_url(),
			'i18n'    => array(
				'added'         => 'Added to cart!',
				'wishlistAdded' => 'Saved to wishlist and added to cart!',
				'selectOptions' => 'Please choose all product options (e.g. design style) first.',
				'addFailed'     => 'Could not add to cart. Please try again.',
			),
		) );
	}
	add_action( 'wp_enqueue_scripts', 'jnpbali_fc_bootstrap', 100 );
}

if ( ! function_exists( 'jnpbali_fc_render' ) ) {
	function jnpbali_fc_render() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}
		$count = (int) WC()->cart->get_cart_contents_count();
		$empty = ( 0 === $count );
		echo '<div class="jnp-float-cart' . ( $empty ? ' is-empty' : '' ) . '" id="jnp-float-cart">';
		echo '<div class="jnp-float-cart__backdrop" aria-hidden="true"></div>';
		echo '<div class="jnp-float-cart__panel" role="dialog" aria-modal="true" aria-label="Shopping cart" hidden>';
		echo '<div class="jnp-float-cart__head"><h2 class="jnp-float-cart__title">Your Cart</h2>';
		echo '<button type="button" class="jnp-float-cart__close" aria-label="Close cart">&times;</button></div>';
		echo '<div class="jnp-float-cart__body"><p class="jnp-float-cart__empty">Your cart is empty.</p></div>';
		echo '<div class="jnp-float-cart__foot"><p class="jnp-float-cart__subtotal"></p>';
		echo '<a class="jnp-float-cart__checkout" href="' . esc_url( wc_get_checkout_url() ) . '">Checkout</a>';
		echo '<a class="jnp-float-cart__view-cart" href="' . esc_url( wc_get_cart_url() ) . '">View full cart</a></div></div>';
		echo '<button type="button" class="jnp-float-cart__btn" aria-expanded="false" aria-label="Open shopping cart">';
		echo '<span class="jnp-float-cart__count" data-count="' . esc_attr( (string) $count ) . '">';
		echo $count > 0 ? esc_html( (string) $count ) : '';
		echo '</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">';
		echo '<path d="M6 6h15l-1.5 9h-12z"></path><circle cx="9" cy="20" r="1.5"></circle><circle cx="18" cy="20" r="1.5"></circle><path d="M6 6L5 3H2"></path></svg></button></div>';
		echo '<div class="jnp-float-cart-toast" id="jnp-float-cart-toast" role="status" aria-live="polite"></div>';
	}
	add_action( 'wp_footer', 'jnpbali_fc_render', 99 );
}

if ( ! function_exists( 'jnpbali_fc_ajax_mini_cart' ) ) {
	function jnpbali_fc_ajax_mini_cart() {
		check_ajax_referer( 'jnpbali_fc_mini_cart', 'nonce' );
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			wp_send_json_error( array( 'message' => 'Cart unavailable' ), 400 );
		}
		jnpbali_fc_send_mini_cart_success();
	}
	add_action( 'wp_ajax_jnpbali_fc_mini_cart', 'jnpbali_fc_ajax_mini_cart' );
	add_action( 'wp_ajax_nopriv_jnpbali_fc_mini_cart', 'jnpbali_fc_ajax_mini_cart' );
}

if ( ! function_exists( 'jnpbali_fc_send_mini_cart_success' ) ) {
	function jnpbali_fc_send_mini_cart_success() {
		$count = WC()->cart->get_cart_contents_count();
		if ( 0 === $count ) {
			wp_send_json_success(
				array(
					'count'    => 0,
					'html'     => '<p class="jnp-float-cart__empty">Your cart is empty.</p>',
					'subtotal' => '',
				)
			);
		}
		ob_start();
		woocommerce_mini_cart();
		$html = ob_get_clean();
		wp_send_json_success(
			array(
				'count'    => $count,
				'html'     => $html,
				'subtotal' => 'Subtotal: <strong>' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '</strong>',
			)
		);
	}
}

if ( ! function_exists( 'jnpbali_fc_ajax_wishlist_add_to_cart' ) ) {
	function jnpbali_fc_ajax_wishlist_add_to_cart() {
		check_ajax_referer( 'jnpbali_fc_mini_cart', 'nonce' );

		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			wp_send_json(
				array(
					'success' => false,
					'data'    => array( 'code' => 'no_cart' ),
				)
			);
		}

		$product_id   = absint( wp_unslash( $_POST['product_id'] ?? 0 ) );
		$variation_id = absint( wp_unslash( $_POST['variation_id'] ?? 0 ) );
		$quantity     = max( 1, absint( wp_unslash( $_POST['quantity'] ?? 1 ) ) );

		if ( ! $product_id ) {
			wp_send_json(
				array(
					'success' => false,
					'data'    => array( 'code' => 'invalid_product' ),
				)
			);
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			wp_send_json(
				array(
					'success' => false,
					'data'    => array( 'code' => 'invalid_product' ),
				)
			);
		}

		$variation = array();
		foreach ( $_POST as $key => $value ) {
			if ( 0 === strpos( $key, 'attribute_' ) ) {
				$clean = wc_clean( wp_unslash( $value ) );
				if ( '' === $clean ) {
					wp_send_json(
						array(
							'success' => false,
							'data'    => array( 'code' => 'select_options' ),
						)
					);
				}
				$variation[ sanitize_title( wp_unslash( $key ) ) ] = $clean;
			}
		}

		if ( $product->is_type( 'variable' ) && ! $variation_id ) {
			wp_send_json(
				array(
					'success' => false,
					'data'    => array( 'code' => 'select_options' ),
				)
			);
		}

		if ( $variation_id ) {
			$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
		} else {
			$added = WC()->cart->add_to_cart( $product_id, $quantity );
		}

		if ( ! $added ) {
			wp_send_json(
				array(
					'success' => false,
					'data'    => array( 'code' => 'add_failed' ),
				)
			);
		}

		jnpbali_fc_send_mini_cart_success();
	}
	add_action( 'wp_ajax_jnpbali_fc_wishlist_add_to_cart', 'jnpbali_fc_ajax_wishlist_add_to_cart' );
	add_action( 'wp_ajax_nopriv_jnpbali_fc_wishlist_add_to_cart', 'jnpbali_fc_ajax_wishlist_add_to_cart' );
}

if ( ! function_exists( 'jnpbali_fc_loop_link' ) ) {
	function jnpbali_fc_loop_link( $link, $product, $args ) {
		if ( ! is_object( $product ) || ! is_a( $product, 'WC_Product' ) ) {
			return $link;
		}
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
	add_filter( 'woocommerce_loop_add_to_cart_link', 'jnpbali_fc_loop_link', 10, 3 );
}
